<?php

namespace App\Domain\ArtificialIntelligence\Services;

use App\Domain\Incidents\Models\Incident;
use App\Domain\Monitoring\Models\Monitor;
use App\Domain\Monitoring\Models\MonitorCheck;
use ImranDevBd\AiHub\Facades\AIHub;
use Throwable;

class AiOperationsCenter
{
    /**
     * Generate an incident summary grounded in telemetry.
     */
    public function summarizeIncident(Incident $incident): string
    {
        $monitor = $incident->monitor;
        $title = $incident->title;
        $severity = $incident->severity;
        $error = $incident->error_classification ?? 'Unknown connectivity failure';
        $detectedAt = $incident->detected_at?->toIso8601String() ?? 'recent';

        try {
            if (config('ai-hub.default') && class_exists(AIHub::class)) {
                $prompt = "You are an SRE incident copilot. Summarize this incident concisely in 2 sentences based strictly on the telemetry:\n".
                    "Service: {$monitor?->name} ({$monitor?->target})\n".
                    "Detected at: {$detectedAt}\n".
                    "Severity: {$severity}\n".
                    "Error classification: {$error}\n".
                    'Latest update: '.($incident->updates()->first()?->message ?? 'Under investigation.');

                return (string) AIHub::prompt($prompt);
            }
        } catch (Throwable) {
            // Graceful fallback to deterministic explanation
        }

        return "Service {$monitor?->name} experienced a {$severity} severity outage starting at {$detectedAt} due to {$error}. SRE automation responded and investigation is ongoing.";
    }

    /**
     * Provide root-cause diagnosis assistance.
     */
    public function diagnoseRootCause(Monitor $monitor, MonitorCheck $check): array
    {
        $type = $monitor->monitor_type;
        $error = $check->error_message ?? 'Connection timed out';
        $code = $check->status_code;

        $hypotheses = [];

        if (str_contains($error, 'SSL') || str_contains($error, 'certificate')) {
            $hypotheses[] = 'TLS certificate expired or handshake failed due to hostname mismatch.';
            $hypotheses[] = 'Check web server ACME / Let\'s Encrypt renewal logs and reverse proxy certificate chain.';
        } elseif ($code >= 500 && $code <= 599) {
            $hypotheses[] = "Upstream application server returned HTTP {$code}.";
            $hypotheses[] = 'Probable cause: application exception, database connection pool exhaustion, or memory exhaustion.';
            $hypotheses[] = 'Action: Inspect backend error logs and verify application container restarts.';
        } elseif ($code === 403 || $code === 401) {
            $hypotheses[] = "Access denied: HTTP {$code}.";
            $hypotheses[] = 'WAF, Cloudflare, or IP rate-limiting rules may be blocking probe requests.';
            $hypotheses[] = 'Action: Whitelist probe IP addresses or verify HTTP User-Agent authorization headers.';
        } elseif (str_contains($error, 'DNS')) {
            $hypotheses[] = 'DNS resolution failed. Nameserver unreachable or domain records modified.';
            $hypotheses[] = 'Action: Verify registrar status, nameserver responsiveness, and authoritative zone files.';
        } else {
            $hypotheses[] = 'Network timeout or unreachable host port.';
            $hypotheses[] = 'Action: Inspect host firewall (iptables/UFW), cloud security groups, and routing gateways.';
        }

        return [
            'confidence' => 'high',
            'observed_error' => $error,
            'primary_hypothesis' => $hypotheses[0] ?? 'General network degradation',
            'recommended_actions' => array_slice($hypotheses, 1),
        ];
    }

    /**
     * Generate non-technical customer-friendly status update.
     */
    public function generateCustomerStatusUpdate(Incident $incident): string
    {
        $service = $incident->monitor?->name ?? 'Our services';
        if ($incident->status === 'resolved') {
            return "All systems for {$service} have recovered and are operating normally. We appreciate your patience while our engineering team resolved this issue.";
        }

        return "We are currently experiencing technical difficulties affecting {$service}. Our operations team has been notified and is actively investigating the situation. We will share further updates shortly.";
    }
}
