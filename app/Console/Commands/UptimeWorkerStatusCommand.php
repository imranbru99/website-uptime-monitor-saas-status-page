<?php

namespace App\Console\Commands;

use App\Domain\Monitoring\Models\MonitoringAgent;
use App\Domain\Monitoring\Models\MonitorLocation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UptimeWorkerStatusCommand extends Command
{
    protected $signature = 'uptime:worker-status';

    protected $description = 'Report operational health of background monitoring workers, queue throughput, and probe agents';

    public function handle(): int
    {
        $this->info('======================================================');
        $this->info('  Website Uptime Monitor — Probe Worker & Queue Status');
        $this->info('======================================================');

        // Queue Backlog
        $pendingJobs = DB::table('jobs')->count();
        $failedJobs = DB::table('failed_jobs')->count();

        $this->line("\n[Queue Telemetry]");
        $this->line("  • Pending Check & Notification Jobs: {$pendingJobs}");
        $this->line("  • Failed Jobs in Dead-Letter Queue:   {$failedJobs}");

        // Probe Nodes
        $this->line("\n[Probe Node Mesh]");
        $locations = MonitorLocation::all();

        $rows = [];
        foreach ($locations as $loc) {
            $rows[] = [
                $loc->name,
                strtoupper($loc->code),
                $loc->city.', '.$loc->country,
                $loc->status,
                $loc->last_heartbeat_at?->diffForHumans() ?? 'Never',
            ];
        }

        $this->table(['Location Name', 'Code', 'Region', 'Status', 'Last Heartbeat'], $rows);

        // Connected Private Agents
        $agentCount = MonitoringAgent::count();
        $this->line("\n[Private Network Agents]: {$agentCount} registered.");

        return Command::SUCCESS;
    }
}
