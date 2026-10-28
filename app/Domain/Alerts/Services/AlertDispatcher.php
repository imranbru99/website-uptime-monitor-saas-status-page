<?php

namespace App\Domain\Alerts\Services;

use App\Domain\Alerts\Jobs\SendAlertNotificationJob;
use App\Domain\Alerts\Models\AlertRule;
use App\Domain\Alerts\Models\NotificationChannel;
use App\Domain\Incidents\Models\Incident;
use App\Domain\Monitoring\Models\Monitor;
use App\Domain\Monitoring\Models\MonitorCheck;

class AlertDispatcher
{
    /**
     * Dispatch alert notifications for a monitor event.
     */
    public function dispatchMonitorAlert(Monitor $monitor, string $eventType, ?MonitorCheck $check = null): void
    {
        // Find matching active alert rules
        $rules = AlertRule::where('tenant_id', $monitor->tenant_id)
            ->where('is_active', true)
            ->where(function ($query) use ($monitor) {
                $query->whereNull('monitor_id')
                    ->orWhere('monitor_id', $monitor->id);
            })
            ->where(function ($query) use ($eventType) {
                $query->where('event_type', $eventType)
                    ->orWhere('event_type', '*');
            })
            ->with('channels')
            ->get();

        $incident = Incident::where('monitor_id', $monitor->id)->latest('detected_at')->first();
        $message = $check ? "Probe check result: {$check->status} ({$check->latency_ms}ms). {$check->error_message}" : null;

        $notifiedChannelIds = [];

        foreach ($rules as $rule) {
            // Check quiet hours suppression
            if ($rule->isWithinQuietHours() && $eventType !== 'down') {
                continue;
            }

            foreach ($rule->channels as $channel) {
                if (! $channel->is_active || in_array($channel->id, $notifiedChannelIds)) {
                    continue;
                }

                $notifiedChannelIds[] = $channel->id;
                SendAlertNotificationJob::dispatch(
                    $channel,
                    $eventType,
                    $monitor,
                    $incident,
                    $message
                );
            }
        }

        // If no explicit rule channels were matched, alert tenant's default active notification channels
        if (empty($notifiedChannelIds)) {
            $defaultChannels = NotificationChannel::where('tenant_id', $monitor->tenant_id)
                ->where('is_active', true)
                ->limit(3)
                ->get();

            foreach ($defaultChannels as $channel) {
                SendAlertNotificationJob::dispatch(
                    $channel,
                    $eventType,
                    $monitor,
                    $incident,
                    $message
                );
            }
        }
    }
}
