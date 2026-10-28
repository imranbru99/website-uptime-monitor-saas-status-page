<?php

namespace App\Console\Commands;

use App\Domain\Monitoring\Models\MonitorCheck;
use App\Domain\Tenancy\Models\Tenant;
use Illuminate\Console\Command;

class UptimeRetentionCommand extends Command
{
    protected $signature = 'uptime:retention {--days= : Custom retention days threshold}';

    protected $description = 'Enforce data retention policies by pruning historical check records past tenant retention periods';

    public function handle(): int
    {
        $this->info('Enforcing data retention policies across tenants...');

        $tenants = Tenant::all();
        $totalPruned = 0;

        foreach ($tenants as $tenant) {
            $days = $this->option('days') ? (int) $this->option('days') : ($tenant->retention_days ?: 30);
            $cutoff = now()->subDays($days);

            $deleted = MonitorCheck::where('tenant_id', $tenant->id)
                ->where('checked_at', '<', $cutoff)
                ->delete();

            $totalPruned += $deleted;
            if ($deleted > 0) {
                $this->line("  • Tenant [{$tenant->name}]: Pruned {$deleted} checks older than {$days} days.");
            }
        }

        $this->info("Retention cleanup completed. Total records pruned: {$totalPruned}");

        return Command::SUCCESS;
    }
}
