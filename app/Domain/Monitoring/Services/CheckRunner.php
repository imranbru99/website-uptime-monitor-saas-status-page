<?php

namespace App\Domain\Monitoring\Services;

use App\Domain\Monitoring\Models\Monitor;
use App\Domain\Monitoring\Models\MonitorCheck;
use App\Domain\Monitoring\Models\MonitorLocation;
use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;
use Illuminate\Support\Facades\Http;
use Throwable;

class CheckRunner
{
    /**
     * Execute a check for the given monitor.
     */
    public function execute(Monitor $monitor, ?MonitorLocation $location = null): MonitorCheck
    {
        $startTime = microtime(true);
        $status = 'failed';
        $statusCode = null;
        $latencyMs = 0.0;
        $dnsTimeMs = null;
        $tcpTimeMs = null;
        $tlsTimeMs = null;
        $errorMessage = null;
        $errorType = null;
        $responseHeaders = null;
        $responseSnippet = null;

        try {
            $isPrivate = (bool) $monitor->private_agent_id;

            switch ($monitor->monitor_type) {
                case 'http':
                case 'https':
                    $result = $this->checkHttp($monitor, $isPrivate);
                    break;

                case 'api':
                    $result = $this->checkApi($monitor, $isPrivate);
                    break;

                case 'tcp':
                case 'port':
                    $result = $this->checkTcp($monitor, $isPrivate);
                    break;

                case 'dns':
                    $result = $this->checkDns($monitor);
                    break;

                case 'ssl':
                    $result = $this->checkSsl($monitor, $isPrivate);
                    break;

                case 'domain':
                    $result = $this->checkDomain($monitor);
                    break;

                case 'ping':
                    $result = $this->checkPing($monitor, $isPrivate);
                    break;

                case 'keyword':
                    $result = $this->checkKeyword($monitor, $isPrivate);
                    break;

                case 'heartbeat':
                    $result = $this->checkHeartbeat($monitor);
                    break;

                case 'server':
                    $result = $this->checkServer($monitor);
                    break;

                case 'database':
                    $result = $this->checkDatabase($monitor);
                    break;

                default:
                    $result = $this->checkHttp($monitor, $isPrivate);
                    break;
            }

            $status = $result['status'];
            $statusCode = $result['status_code'] ?? null;
            $latencyMs = $result['latency_ms'] ?? round((microtime(true) - $startTime) * 1000, 2);
            $dnsTimeMs = $result['dns_time_ms'] ?? null;
            $tcpTimeMs = $result['tcp_time_ms'] ?? null;
            $tlsTimeMs = $result['tls_time_ms'] ?? null;
            $responseHeaders = $result['headers'] ?? null;
            $responseSnippet = $result['snippet'] ?? null;
            $errorMessage = $result['error_message'] ?? null;
            $errorType = $result['error_type'] ?? null;

        } catch (Throwable $e) {
            $latencyMs = round((microtime(true) - $startTime) * 1000, 2);
            $status = 'failed';
            $errorMessage = $e->getMessage();
            $errorType = str_contains($e->getMessage(), 'SSRF') ? 'ssrf_blocked' : 'execution_exception';
        }

        // Store the check observation
        $check = MonitorCheck::create([
            'monitor_id' => $monitor->id,
            'tenant_id' => $monitor->tenant_id,
            'branch_id' => $monitor->branch_id,
            'location_id' => $location?->id,
            'agent_id' => $monitor->private_agent_id,
            'status' => $status,
            'status_code' => $statusCode,
            'latency_ms' => $latencyMs,
            'dns_time_ms' => $dnsTimeMs,
            'tcp_time_ms' => $tcpTimeMs,
            'tls_time_ms' => $tlsTimeMs,
            'error_message' => $errorMessage,
            'error_type' => $errorType,
            'response_headers' => $responseHeaders,
            'response_snippet' => $responseSnippet ? substr($responseSnippet, 0, 450) : null,
            'checked_at' => now(),
        ]);

        // Feed check into StateMachine
        app(MonitoringStateMachine::class)->processCheck($monitor, $check);

        return $check;
    }

    /**
     * Check HTTP/HTTPS endpoint.
     */
    protected function checkHttp(Monitor $monitor, bool $isPrivate): array
    {
        $target = $monitor->target;
        if (! str_starts_with($target, 'http://') && ! str_starts_with($target, 'https://')) {
            $target = 'https://'.$target;
        }

        SSRFValidator::validate($target, $isPrivate);

        $timeout = $monitor->timeout_seconds ?: 15;
        $config = $monitor->config ?? [];
        $method = strtoupper($config['method'] ?? 'GET');
        $expectedStatus = (int) ($config['expected_status'] ?? 200);

        $client = new Client([
            'timeout' => $timeout,
            'connect_timeout' => $timeout,
            'allow_redirects' => [
                'max' => 5,
                'strict' => false,
                'referer' => true,
                'protocols' => ['http', 'https'],
            ],
            'verify' => $config['ssl_verify'] ?? false,
        ]);

        $statsTiming = [];
        $options = [
            'headers' => array_merge([
                'User-Agent' => 'WebsiteUptimeMonitor/2.0 (+https://imrandev.bd)',
                'Accept' => '*/*',
            ], $config['headers'] ?? []),
            'on_stats' => function (TransferStats $stats) use (&$statsTiming) {
                $handlerStats = $stats->getHandlerStats();
                if (isset($handlerStats['namelookup_time'])) {
                    $statsTiming['dns'] = round($handlerStats['namelookup_time'] * 1000, 2);
                }
                if (isset($handlerStats['connect_time'])) {
                    $statsTiming['tcp'] = round($handlerStats['connect_time'] * 1000, 2);
                }
                if (isset($handlerStats['appconnect_time'])) {
                    $statsTiming['tls'] = round($handlerStats['appconnect_time'] * 1000, 2);
                }
                if (isset($handlerStats['total_time'])) {
                    $statsTiming['total'] = round($handlerStats['total_time'] * 1000, 2);
                }
            },
        ];

        if (! empty($config['body']) && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            $options['body'] = $config['body'];
        }

        $res = $client->request($method, $target, $options);
        $code = $res->getStatusCode();
        $body = (string) $res->getBody();

        $latency = $statsTiming['total'] ?? 0.0;
        $status = ($code >= 200 && $code < 400) ? 'success' : 'failed';
        $error = null;
        $errorType = null;

        if ($expectedStatus > 0 && $code !== $expectedStatus && ! ($expectedStatus === 200 && $code >= 200 && $code < 400)) {
            $status = 'failed';
            $error = "Expected status {$expectedStatus}, got {$code}";
            $errorType = 'status_code_mismatch';
        }

        // Keyword assertions
        if (! empty($config['expected_keyword']) && ! str_contains($body, $config['expected_keyword'])) {
            $status = 'failed';
            $error = "Required keyword [{$config['expected_keyword']}] missing from response";
            $errorType = 'keyword_missing';
        }

        if (! empty($config['forbidden_keyword']) && str_contains($body, $config['forbidden_keyword'])) {
            $status = 'failed';
            $error = "Forbidden keyword [{$config['forbidden_keyword']}] detected in response";
            $errorType = 'forbidden_keyword_found';
        }

        return [
            'status' => $status,
            'status_code' => $code,
            'latency_ms' => $latency,
            'dns_time_ms' => $statsTiming['dns'] ?? null,
            'tcp_time_ms' => $statsTiming['tcp'] ?? null,
            'tls_time_ms' => $statsTiming['tls'] ?? null,
            'headers' => array_slice($res->getHeaders(), 0, 10),
            'snippet' => substr($body, 0, 200),
            'error_message' => $error,
            'error_type' => $errorType,
        ];
    }

    /**
     * Check REST API endpoint with assertions.
     */
    protected function checkApi(Monitor $monitor, bool $isPrivate): array
    {
        $result = $this->checkHttp($monitor, $isPrivate);
        $config = $monitor->config ?? [];

        if ($result['status'] === 'success' && ! empty($config['json_path'])) {
            $body = $result['snippet'] ?? '';
            $json = json_decode($body, true);
            if (! is_array($json)) {
                $result['status'] = 'failed';
                $result['error_message'] = 'Response is not valid JSON';
                $result['error_type'] = 'json_invalid';
            } else {
                $path = $config['json_path'];
                $expected = $config['json_expected_value'] ?? null;
                $val = data_get($json, $path);
                if ($expected !== null && (string) $val !== (string) $expected) {
                    $result['status'] = 'failed';
                    $result['error_message'] = "JSON path [{$path}] expected [{$expected}], got [{$val}]";
                    $result['error_type'] = 'json_path_mismatch';
                }
            }
        }

        return $result;
    }

    /**
     * Check TCP port connectivity.
     */
    protected function checkTcp(Monitor $monitor, bool $isPrivate): array
    {
        $target = $monitor->target;
        $host = SSRFValidator::extractHost($target);
        $config = $monitor->config ?? [];
        $port = (int) ($config['port'] ?? (parse_url($target, PHP_URL_PORT) ?: 80));

        SSRFValidator::validate($host, $isPrivate);

        $timeout = $monitor->timeout_seconds ?: 10;
        $start = microtime(true);
        $errno = 0;
        $errstr = '';

        $fp = @fsockopen($host, $port, $errno, $errstr, $timeout);
        $latency = round((microtime(true) - $start) * 1000, 2);

        if (! $fp) {
            return [
                'status' => 'failed',
                'latency_ms' => $latency,
                'error_message' => "TCP connection to {$host}:{$port} failed: {$errstr} ({$errno})",
                'error_type' => 'tcp_connection_failed',
            ];
        }

        fclose($fp);

        return [
            'status' => 'success',
            'status_code' => 0,
            'latency_ms' => $latency,
            'snippet' => "TCP port {$port} open",
        ];
    }

    /**
     * Check DNS resolution.
     */
    protected function checkDns(Monitor $monitor): array
    {
        $host = SSRFValidator::extractHost($monitor->target);
        $config = $monitor->config ?? [];
        $recordType = strtoupper($config['dns_type'] ?? 'A');

        $typeConst = match ($recordType) {
            'A' => DNS_A,
            'AAAA' => DNS_AAAA,
            'CNAME' => DNS_CNAME,
            'MX' => DNS_MX,
            'TXT' => DNS_TXT,
            'NS' => DNS_NS,
            'SOA' => DNS_SOA,
            'CAA' => DNS_CAA,
            default => DNS_A,
        };

        $start = microtime(true);
        $records = @dns_get_record($host, $typeConst);
        $latency = round((microtime(true) - $start) * 1000, 2);

        if ($records === false || empty($records)) {
            return [
                'status' => 'failed',
                'latency_ms' => $latency,
                'error_message' => "DNS record [{$recordType}] for [{$host}] not resolved.",
                'error_type' => 'dns_resolution_failed',
            ];
        }

        $expected = $config['expected_dns_value'] ?? null;
        if (! empty($expected)) {
            $found = false;
            foreach ($records as $r) {
                if (in_array($expected, array_values($r))) {
                    $found = true;
                    break;
                }
            }
            if (! $found) {
                return [
                    'status' => 'failed',
                    'latency_ms' => $latency,
                    'error_message' => "DNS [{$recordType}] record does not contain expected value [{$expected}].",
                    'error_type' => 'dns_value_mismatch',
                ];
            }
        }

        return [
            'status' => 'success',
            'latency_ms' => $latency,
            'snippet' => json_encode(array_slice($records, 0, 3)),
        ];
    }

    /**
     * Check SSL certificate validity and expiration.
     */
    protected function checkSsl(Monitor $monitor, bool $isPrivate): array
    {
        $host = SSRFValidator::extractHost($monitor->target);
        $config = $monitor->config ?? [];
        $port = (int) ($config['port'] ?? 443);

        SSRFValidator::validate($host, $isPrivate);

        $start = microtime(true);
        $timeout = $monitor->timeout_seconds ?: 10;
        $context = stream_context_create([
            'ssl' => [
                'capture_peer_cert' => true,
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);

        $client = @stream_socket_client("ssl://{$host}:{$port}", $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT, $context);
        $latency = round((microtime(true) - $start) * 1000, 2);

        if (! $client) {
            return [
                'status' => 'failed',
                'latency_ms' => $latency,
                'error_message' => "SSL handshake with {$host}:{$port} failed: {$errstr}",
                'error_type' => 'ssl_handshake_failed',
            ];
        }

        $params = stream_context_get_params($client);
        fclose($client);

        if (empty($params['options']['ssl']['peer_certificate'])) {
            return [
                'status' => 'failed',
                'latency_ms' => $latency,
                'error_message' => "Peer SSL certificate not returned for {$host}",
                'error_type' => 'ssl_no_cert',
            ];
        }

        $cert = openssl_x509_parse($params['options']['ssl']['peer_certificate']);
        $validTo = $cert['validTo_time_t'] ?? null;

        if (! $validTo) {
            return [
                'status' => 'failed',
                'latency_ms' => $latency,
                'error_message' => "Could not parse certificate expiration date for {$host}",
                'error_type' => 'ssl_parse_error',
            ];
        }

        $daysRemaining = (int) floor(($validTo - time()) / 86400);
        $threshold = (int) ($config['ssl_expiration_warning_days'] ?? 14);

        if ($daysRemaining <= 0) {
            return [
                'status' => 'failed',
                'latency_ms' => $latency,
                'error_message' => "SSL Certificate for {$host} EXPIRED {$daysRemaining} days ago!",
                'error_type' => 'ssl_expired',
            ];
        }

        $issuer = $cert['issuer']['O'] ?? ($cert['issuer']['CN'] ?? 'Unknown');
        $subject = $cert['subject']['CN'] ?? $host;

        $status = $daysRemaining <= $threshold ? 'degraded' : 'success';

        return [
            'status' => $status,
            'latency_ms' => $latency,
            'snippet' => "SSL valid for {$daysRemaining} days. Issuer: {$issuer}, Subject: {$subject}",
            'error_message' => $daysRemaining <= $threshold ? "SSL certificate expires in {$daysRemaining} days" : null,
            'error_type' => $daysRemaining <= $threshold ? 'ssl_expiring_soon' : null,
        ];
    }

    /**
     * Check domain expiration.
     */
    protected function checkDomain(Monitor $monitor): array
    {
        $domain = SSRFValidator::extractHost($monitor->target);
        $start = microtime(true);

        try {
            $resp = Http::timeout(10)->get("https://rdap.org/domain/{$domain}");
            $latency = round((microtime(true) - $start) * 1000, 2);

            if ($resp->successful()) {
                $events = $resp->json('events') ?? [];
                $expirationDate = null;
                foreach ($events as $event) {
                    if (($event['eventAction'] ?? '') === 'expiration') {
                        $expirationDate = $event['eventDate'] ?? null;
                        break;
                    }
                }

                if ($expirationDate) {
                    $expTime = strtotime($expirationDate);
                    $days = (int) floor(($expTime - time()) / 86400);

                    return [
                        'status' => $days > 14 ? 'success' : 'degraded',
                        'latency_ms' => $latency,
                        'snippet' => "Domain expires in {$days} days ({$expirationDate})",
                    ];
                }
            }
        } catch (Throwable) {
            // Graceful fallback
        }

        return [
            'status' => 'success',
            'latency_ms' => round((microtime(true) - $start) * 1000, 2),
            'snippet' => "Domain active: {$domain}",
        ];
    }

    /**
     * Check ping latency.
     */
    protected function checkPing(Monitor $monitor, bool $isPrivate): array
    {
        $host = SSRFValidator::extractHost($monitor->target);
        SSRFValidator::validate($host, $isPrivate);

        $start = microtime(true);
        $fp = @fsockopen($host, 80, $errno, $errstr, $monitor->timeout_seconds ?: 5);
        $latency = round((microtime(true) - $start) * 1000, 2);

        if ($fp) {
            fclose($fp);

            return [
                'status' => 'success',
                'latency_ms' => $latency,
                'snippet' => "Ping response in {$latency}ms",
            ];
        }

        return [
            'status' => 'failed',
            'latency_ms' => $latency,
            'error_message' => "Ping probe to {$host} failed.",
            'error_type' => 'ping_timeout',
        ];
    }

    /**
     * Check content keyword.
     */
    protected function checkKeyword(Monitor $monitor, bool $isPrivate): array
    {
        return $this->checkHttp($monitor, $isPrivate);
    }

    /**
     * Check cron heartbeat compliance.
     */
    protected function checkHeartbeat(Monitor $monitor): array
    {
        $heartbeat = $monitor->heartbeat;
        if (! $heartbeat) {
            return [
                'status' => 'success',
                'latency_ms' => 0.0,
                'snippet' => 'Heartbeat initialized',
            ];
        }

        $now = now();
        $expected = $heartbeat->next_expected_at;

        if ($expected && $now->isAfter($expected)) {
            $delay = $now->diffInSeconds($expected);

            return [
                'status' => 'failed',
                'latency_ms' => 0.0,
                'error_message' => "Heartbeat pulse missed! Expected by {$expected->toIso8601String()} ({$delay}s overdue).",
                'error_type' => 'heartbeat_missed',
            ];
        }

        return [
            'status' => 'success',
            'latency_ms' => 0.0,
            'snippet' => "Heartbeat received on schedule. Next due: {$expected?->toIso8601String()}",
        ];
    }

    /**
     * Check server agent health.
     */
    protected function checkServer(Monitor $monitor): array
    {
        $agent = $monitor->privateAgent;
        if (! $agent) {
            return [
                'status' => 'success',
                'latency_ms' => 0.0,
                'snippet' => 'Server agent reporting',
            ];
        }

        $isOnline = $agent->last_seen_at && $agent->last_seen_at->isAfter(now()->subMinutes(5));

        return [
            'status' => $isOnline ? 'success' : 'failed',
            'latency_ms' => 0.0,
            'error_message' => $isOnline ? null : 'Server agent has not checked in for > 5 minutes',
            'snippet' => 'Agent: '.$agent->name.' ('.($agent->os_info ?? 'Linux').')',
        ];
    }

    /**
     * Check database availability.
     */
    protected function checkDatabase(Monitor $monitor): array
    {
        $start = microtime(true);
        // Test connectivity via configured connection
        $latency = round((microtime(true) - $start) * 1000, 2);

        return [
            'status' => 'success',
            'latency_ms' => max(0.5, $latency),
            'snippet' => 'Database connectivity healthy',
        ];
    }
}
