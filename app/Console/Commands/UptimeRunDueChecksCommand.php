<?php

namespace App\Console\Commands;

use App\Domain\Monitoring\Services\MonitoringScheduler;
use Illuminate\Console\Command;

class UptimeRunDueChecksCommand extends Command
{
    protected $signature = 'uptime:run-due-checks';

    protected $description = 'Dispatch pending monitoring probes whose check intervals are due';

    public function handle(MonitoringScheduler $scheduler): int
    {
        $dispatched = $scheduler->dispatchDueChecks();
        $this->line("Dispatched {$dispatched} monitor check jobs to queue.");

        return Command::SUCCESS;
    }
}
