<?php

namespace App\Domain\Monitoring\Jobs;

use App\Domain\Monitoring\Models\Monitor;
use App\Domain\Monitoring\Models\MonitorLocation;
use App\Domain\Monitoring\Services\CheckRunner;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PerformMonitorCheckJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public int $timeout = 60;

    public function __construct(
        public Monitor $monitor,
        public ?MonitorLocation $location = null
    ) {}

    public function handle(CheckRunner $runner): void
    {
        if (! $this->monitor->is_active || $this->monitor->is_paused) {
            return;
        }

        $runner->execute($this->monitor, $this->location);
    }
}
