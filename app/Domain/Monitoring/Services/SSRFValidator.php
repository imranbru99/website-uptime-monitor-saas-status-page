<?php

namespace App\Domain\Monitoring\Services;

use Exception;

class SSRFValidator
{
    /**
     * Reserved and private IP ranges that public probes must NEVER access.
     */
    protected static array $blockedIpRanges = [
        '127.0.0.0/8',      // Loopback
        '10.0.0.0/8',       // RFC1918 Class A
        '172.16.0.0/12',    // RFC1918 Class B
        '192.168.0.0/16',   // RFC1918 Class C
        '169.254.0.0/16',   // Link-local / Cloud Metadata
        '0.0.0.0/8',        // Current network
        '100.64.0.0/10',    // Shared Address Space (CGNAT)
        '192.0.0.0/24',     // IETF Protocol Assignments
        '192.0.2.0/24',     // Documentation (TEST-NET-1)
        '198.51.100.0/24',  // Documentation (TEST-NET-2)
        '203.0.113.0/24',   // Documentation (TEST-NET-3)
        '224.0.0.0/4',      // Multicast
        '240.0.0.0/4',      // Reserved
        '::1/128',          // IPv6 Loopback
        'fc00::/7',         // IPv6 Unique Local
        'fe80::/10',        // IPv6 Link-local
    ];

    /**
     * Validate an arbitrary URL or host string against SSRF attack patterns.
     *
     * @throws Exception if target violates SSRF policy
     */
    public static function validate(string $target, bool $allowPrivate = false): void
    {
        if ($allowPrivate) {
            return; // Configured for private internal agent probe
        }

        // Check if scheme is forbidden before anything else
        if (str_contains($target, '://')) {
            $scheme = strtolower(parse_url($target, PHP_URL_SCHEME) ?? '');
            if (! in_array($scheme, ['http', 'https', 'tcp', 'ssl', 'tls'])) {
                throw new Exception("SSRF Protection: URL scheme [{$scheme}] is forbidden.");
            }
        }

        $host = static::extractHost($target);

        if (empty($host)) {
            throw new Exception('Invalid target host: empty or malformed.');
        }

        // Check dangerous literal hostnames
        $lowerHost = strtolower($host);
        if (in_array($lowerHost, ['localhost', 'metadata.google.internal', 'instance-data', '169.254.169.254'])) {
            throw new Exception("SSRF Protection: Access to host [{$host}] is blocked.");
        }

        // Resolve DNS to verify IP addresses
        $ips = static::resolveIps($host);
        if (empty($ips)) {
            // If it cannot resolve, let the actual check handle DNS resolution failure
            return;
        }

        foreach ($ips as $ip) {
            if (static::isBlockedIp($ip)) {
                throw new Exception("SSRF Protection: Host [{$host}] resolves to forbidden IP [{$ip}] (blocked).");
            }
        }
    }

    /**
     * Check if an IP address falls within any blocked CIDR range.
     */
    public static function isBlockedIp(string $ip): bool
    {
        // Filter out loopback and private IPs via PHP filter if supported
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return true;
        }

        foreach (static::$blockedIpRanges as $cidr) {
            if (static::ipMatchesCidr($ip, $cidr)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Extract host from URL or bare domain:port string.
     */
    public static function extractHost(string $target): string
    {
        if (! str_contains($target, '://')) {
            $parts = explode(':', $target);

            return $parts[0];
        }

        $parsed = parse_url($target);

        return $parsed['host'] ?? '';
    }

    /**
     * Resolve all IPs (IPv4 and IPv6) for a given hostname.
     */
    public static function resolveIps(string $host): array
    {
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            return [$host];
        }

        $ips = [];
        $records = @dns_get_record($host, DNS_A + DNS_AAAA);

        if (is_array($records)) {
            foreach ($records as $record) {
                if (isset($record['ip'])) {
                    $ips[] = $record['ip'];
                } elseif (isset($record['ipv6'])) {
                    $ips[] = $record['ipv6'];
                }
            }
        }

        return $ips;
    }

    /**
     * Match IP against CIDR notation.
     */
    protected static function ipMatchesCidr(string $ip, string $cidr): bool
    {
        if (! str_contains($cidr, '/')) {
            return $ip === $cidr;
        }

        [$net, $mask] = explode('/', $cidr);

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && filter_var($net, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $ipLong = ip2long($ip);
            $netLong = ip2long($net);
            $maskLong = -1 << (32 - (int) $mask);

            return ($ipLong & $maskLong) === ($netLong & $maskLong);
        }

        return false;
    }
}
