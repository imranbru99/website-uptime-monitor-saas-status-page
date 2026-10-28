<?php

namespace App\Domain\Alerts\Jobs;

use App\Domain\Alerts\Models\NotificationChannel;
use App\Domain\Alerts\Services\NotificationDeliveryService;
use App\Domain\Incidents\Models\Incident;
use App\Domain\Monitoring\Models\Monitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAlertNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 30;

    public function __construct(
        public NotificationChannel $channel,
        public string $eventType,
        public Monitor $monitor,
        public ?Incident $incident = null,
        public ?string $customMessage = null
    ) {}

    public function handle(NotificationDeliveryService $service): void
    {
        $service->deliver(
            $this->channel,
            $this->eventType,
            $this->monitor,
            $this->incident,
            $this->customMessage
        );
    }
}
