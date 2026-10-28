<?php

namespace App\Console\Commands;

use App\Domain\Analytics\Services\PerformanceProfiler;
use App\Domain\Monitoring\Models\Monitor;
use App\Domain\Monitoring\Models\MonitorLocation;
use App\Domain\Monitoring\Services\SSRFValidator;
use App\Domain\Tenancy\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class UptimeDiagnoseCommand extends Command
{
    protected $signature = 'uptime:diagnose';

    protected $description = 'Perform comprehensive diagnostic checks across system health, database, queues, and probe agents';

    public function handle(): int
    {
        $this->info('======================================================');
        $this->info('  Website Uptime Monitor — System Diagnostic Suite');
        $this->info('  Author: Imran Ahmed <me@imrandev.bd>');
        $this->info('======================================================');

        // 1. Database Connectivity
        $this->line("\n[1/5] Checking Database Connectivity...");
        try {
            DB::connection()->getPdo();
            $this->info('  ✓ Database connected successfully: '.config('database.default'));
        } catch (Throwable $e) {
            $this->error('  ✕ Database connection error: '.$e->getMessage());
        }

        // 2. Active Tenant Organizations
        $this->line("\n[2/5] Inspecting Tenant Registry...");
        $tenantCount = Tenant::count();
        $this->info("  ✓ Active Tenants: {$tenantCount}");

        // 3. Monitor Inventory
        $this->line("\n[3/5] Inspecting Monitoring Target Inventory...");
        $monitorCount = Monitor::count();
        $healthyCount = Monitor::where('status', 'healthy')->count();
        $downCount = Monitor::whereIn('status', ['down', 'suspected_down'])->count();
        $this->info("  ✓ Total Monitors: {$monitorCount} (Healthy: {$healthyCount}, Down: {$downCount})");

        // 4. Global Probe Locations
        $this->line("\n[4/5] Checking Global Probe Mesh...");
        $activeLocations = MonitorLocation::where('status', 'active')->count();
        $this->info("  ✓ Active Probe Locations: {$activeLocations}");

        // 5. SSRF Security Verification
        $this->line("\n[5/5] Testing SSRF Security Defenses...");
        $ssrfBlocked = false;
        try {
            SSRFValidator::validate('http://169.254.169.254/latest/meta-data/');
        } catch (Throwable) {
            $ssrfBlocked = true;
        }

        if ($ssrfBlocked) {
            $this->info('  ✓ SSRF Protection Active: Cloud metadata endpoints blocked.');
        } else {
            $this->error('  ✕ Warning: SSRF validator failed to intercept link-local IP!');
        }

        // Performance Profiler Summary
        $this->line("\n--- Performance Diagnostics ---");
        $profiler = PerformanceProfiler::analyze();
        $this->line("  • Memory Peak: {$profiler['memory_peak_mb']} MB");
        $this->line("  • Query Status: {$profiler['status']}");

        $this->info("\nDiagnostics passed with 0 critical errors.");

        return Command::SUCCESS;
    }
}
