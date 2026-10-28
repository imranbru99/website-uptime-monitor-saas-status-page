<?php

namespace App\Domain\Incidents\Services;

use App\Domain\Incidents\Models\Incident;
use App\Domain\Monitoring\Models\Monitor;
use App\Domain\Monitoring\Models\MonitorCheck;

class IncidentManager
{
    public function __construct(
        protected CorrelationEngine $correlationEngine
    ) {}

    /**
     * Handle monitor failure and create or associate incident.
     */
    public function handleMonitorDown(Monitor $monitor, MonitorCheck $check): Incident
    {
        // Check for existing open incident for this monitor
        $existing = Incident::where('monitor_id', $monitor->id)
            ->whereIn('status', ['detected', 'confirmed', 'acknowledged', 'investigating', 'identified', 'monitoring_recovery'])
            ->latest('detected_at')
            ->first();

        if ($existing) {
            $existing->updates()->create([
                'status' => 'investigating',
                'message' => "Continuous outage observed: {$check->error_message} (Latency: {$check->latency_ms}ms).",
                'is_public' => false,
                'posted_at' => now(),
            ]);

            return $existing;
        }

        // Check for correlated parent outage (e.g. shared cluster/datacenter outage)
        $parentIncident = $this->correlationEngine->findCorrelatedParent($monitor);

        $severity = match ($monitor->monitor_type) {
            'database', 'server' => 'critical',
            'api' => 'high',
            'http', 'https' => 'high',
            'ssl', 'dns' => 'medium',
            default => 'high',
        };

        $incident = Incident::create([
            'tenant_id' => $monitor->tenant_id,
            'branch_id' => $monitor->branch_id,
            'monitor_id' => $monitor->id,
            'title' => "Service Outage: {$monitor->name}",
            'description' => "Target [{$monitor->target}] reported down: {$check->error_message}",
            'severity' => $severity,
            'status' => 'confirmed',
            'detected_at' => now(),
            'error_classification' => $check->error_type ?? 'network_timeout',
            'impacted_services' => [$monitor->name],
            'tags' => $monitor->tags ?? ['outage', $monitor->monitor_type],
            'is_correlated' => $parentIncident !== null,
            'parent_incident_id' => $parentIncident?->id,
        ]);

        $incident->updates()->create([
            'status' => 'investigating',
            'message' => "Automated incident created. Probe detected failure: {$check->error_message}",
            'is_public' => true,
            'posted_at' => now(),
        ]);

        return $incident;
    }

    /**
     * Handle monitor recovery and auto-resolve open incident.
     */
    public function handleMonitorRecovered(Monitor $monitor, MonitorCheck $check): void
    {
        $incident = Incident::where('monitor_id', $monitor->id)
            ->whereIn('status', ['detected', 'confirmed', 'acknowledged', 'investigating', 'identified', 'monitoring_recovery'])
            ->latest('detected_at')
            ->first();

        if ($incident) {
            $incident->update([
                'status' => 'resolved',
                'resolved_at' => now(),
                'resolution_summary' => "Probe confirmed service recovered with {$check->latency_ms}ms response time.",
            ]);

            $incident->updates()->create([
                'status' => 'resolved',
                'message' => "Automated recovery verified. Service response latency: {$check->latency_ms}ms.",
                'is_public' => true,
                'posted_at' => now(),
            ]);
        }
    }
}
