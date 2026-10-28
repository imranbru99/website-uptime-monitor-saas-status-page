<?php

namespace App\Domain\Monitoring\Services;

use App\Domain\Monitoring\Jobs\PerformMonitorCheckJob;
use App\Domain\Monitoring\Models\Monitor;
use App\Domain\Monitoring\Models\MonitorLocation;

class MonitoringScheduler
{
    /**
     * Dispatch checks for all monitors due for evaluation.
     */
    public function dispatchDueChecks(): int
    {
        $dueMonitors = Monitor::where('is_active', true)
            ->where('is_paused', false)
            ->where(function ($query) {
                $query->whereNull('next_check_due_at')
                    ->orWhere('next_check_due_at', '<=', now());
            })
            ->limit(100)
            ->get();

        $dispatched = 0;
        $primaryLocation = MonitorLocation::where('status', 'active')->first();

        foreach ($dueMonitors as $monitor) {
            // Schedule next check time immediately to prevent duplicate dispatch
            $interval = max(10, $monitor->check_interval_seconds ?: 60);
            $monitor->next_check_due_at = now()->addSeconds($interval);
            $monitor->saveQuietly();

            PerformMonitorCheckJob::dispatch($monitor, $primaryLocation);
            $dispatched++;
        }

        return $dispatched;
    }
}
