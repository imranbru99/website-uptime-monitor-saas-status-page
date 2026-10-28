<?php

namespace App\Domain\Monitoring\Services;

use App\Domain\Alerts\Services\AlertDispatcher;
use App\Domain\Incidents\Services\IncidentManager;
use App\Domain\Monitoring\Models\Monitor;
use App\Domain\Monitoring\Models\MonitorCheck;
use App\Domain\Monitoring\Models\MonitorStatusTransition;

class MonitoringStateMachine
{
    /**
     * Process an incoming check observation and evaluate monitor state.
     */
    public function processCheck(Monitor $monitor, MonitorCheck $check): void
    {
        $oldStatus = $monitor->status;
        $newStatus = $oldStatus;
        $reason = null;

        if ($monitor->is_paused) {
            $newStatus = 'paused';
        } elseif ($monitor->status === 'maintenance') {
            // Keep in maintenance during active maintenance windows
            $newStatus = 'maintenance';
        } elseif ($check->status === 'failed') {
            $monitor->consecutive_failures++;
            $monitor->consecutive_successes = 0;

            if ($monitor->consecutive_failures >= $monitor->confirmation_threshold) {
                $newStatus = 'down';
                $reason = "Confirmed down: {$monitor->consecutive_failures} consecutive failures. {$check->error_message}";
            } else {
                $newStatus = 'suspected_down';
                $reason = "Suspected down: {$monitor->consecutive_failures} failure(s) observed. Threshold is {$monitor->confirmation_threshold}.";
            }
        } elseif ($check->status === 'degraded') {
            $monitor->consecutive_successes++;
            $monitor->consecutive_failures = 0;
            $newStatus = 'degraded';
            $reason = "Performance degraded: {$check->error_message}";
        } else { // success
            $monitor->consecutive_successes++;
            $monitor->consecutive_failures = 0;

            if (in_array($oldStatus, ['down', 'suspected_down'])) {
                $newStatus = 'recovering';
                $reason = "Recovering: First successful check observed ({$check->latency_ms}ms).";
            } elseif ($oldStatus === 'recovering' || $oldStatus === 'pending') {
                $newStatus = 'healthy';
                $reason = 'Healthy: Confirmed operational.';
            } else {
                $newStatus = 'healthy';
            }
        }

        // Calculate moving average latency
        if ($check->latency_ms > 0) {
            $currentAvg = (float) $monitor->avg_latency_ms;
            $monitor->avg_latency_ms = $currentAvg > 0
                ? round(($currentAvg * 0.8) + ($check->latency_ms * 0.2), 2)
                : $check->latency_ms;
        }

        $monitor->last_checked_at = now();

        // Check if state changed
        if ($newStatus !== $oldStatus) {
            $monitor->status = $newStatus;
            $monitor->last_status_change_at = now();

            // Record state transition
            MonitorStatusTransition::create([
                'monitor_id' => $monitor->id,
                'tenant_id' => $monitor->tenant_id,
                'from_status' => $oldStatus,
                'to_status' => $newStatus,
                'reason' => $reason,
                'trigger_check_id' => $check->id,
                'created_at' => now(),
            ]);

            // Lifecycle triggers
            if ($newStatus === 'down') {
                // Auto create or correlate incident
                app(IncidentManager::class)->handleMonitorDown($monitor, $check);
                // Dispatch alert
                app(AlertDispatcher::class)->dispatchMonitorAlert($monitor, 'down', $check);
            } elseif ($newStatus === 'healthy' && in_array($oldStatus, ['down', 'recovering', 'suspected_down'])) {
                // Auto resolve incident
                app(IncidentManager::class)->handleMonitorRecovered($monitor, $check);
                // Dispatch recovery alert
                app(AlertDispatcher::class)->dispatchMonitorAlert($monitor, 'recovered', $check);
            }
        }

        $monitor->save();
    }
}
