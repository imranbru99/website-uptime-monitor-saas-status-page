<?php

namespace App\Console\Commands;

use App\Domain\Billing\Models\SubscriptionPlan;
use App\Domain\Modules\Services\ModuleRegistry;
use App\Domain\Monitoring\Models\MonitorLocation;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class UptimeInstallCommand extends Command
{
    protected $signature = 'uptime:install {--force : Overwrite existing setup}';

    protected $description = 'Complete installation and bootstrapping of the Website Uptime Monitor SaaS platform';

    public function handle(): int
    {
        $this->info('====================================================');
        $this->info('  Website Uptime Monitor — Enterprise Installation');
        $this->info('  Author: Imran Ahmed <me@imrandev.bd>');
        $this->info('  Website: https://imrandev.bd');
        $this->info('====================================================');

        $this->info('Step 1/5: Running Database Migrations...');
        Artisan::call('migrate', ['--force' => true], $this->output);

        $this->info('Step 2/5: Synchronizing 60 Core Modules Registry...');
        ModuleRegistry::syncModulesToDatabase();
        $this->line('  ✓ 60 Modules synchronized.');

        $this->info('Step 3/5: Seeding Global Probe Locations...');
        $locations = [
            ['code' => 'sin', 'name' => 'Singapore Primary Probe', 'city' => 'Singapore', 'country' => 'SG', 'region' => 'Asia Pacific', 'latitude' => 1.3521, 'longitude' => 103.8198, 'status' => 'active'],
            ['code' => 'tyo', 'name' => 'Tokyo Probe Node', 'city' => 'Tokyo', 'country' => 'JP', 'region' => 'Asia Pacific', 'latitude' => 35.6762, 'longitude' => 139.6503, 'status' => 'active'],
            ['code' => 'lhr', 'name' => 'London Probe Node', 'city' => 'London', 'country' => 'GB', 'region' => 'Europe', 'latitude' => 51.5074, 'longitude' => -0.1278, 'status' => 'active'],
            ['code' => 'fra', 'name' => 'Frankfurt Probe Node', 'city' => 'Frankfurt', 'country' => 'DE', 'region' => 'Europe', 'latitude' => 50.1109, 'longitude' => 8.6821, 'status' => 'active'],
            ['code' => 'nyc', 'name' => 'New York Probe Node', 'city' => 'New York', 'country' => 'US', 'region' => 'North America', 'latitude' => 40.7128, 'longitude' => -74.0060, 'status' => 'active'],
            ['code' => 'bom', 'name' => 'Mumbai Probe Node', 'city' => 'Mumbai', 'country' => 'IN', 'region' => 'South Asia', 'latitude' => 19.0760, 'longitude' => 72.8777, 'status' => 'active'],
            ['code' => 'syd', 'name' => 'Sydney Probe Node', 'city' => 'Sydney', 'country' => 'AU', 'region' => 'Oceania', 'latitude' => -33.8688, 'longitude' => 151.2093, 'status' => 'active'],
            ['code' => 'gru', 'name' => 'São Paulo Probe Node', 'city' => 'São Paulo', 'country' => 'BR', 'region' => 'South America', 'latitude' => -23.5505, 'longitude' => -46.6333, 'status' => 'active'],
        ];

        foreach ($locations as $loc) {
            MonitorLocation::updateOrCreate(
                ['code' => $loc['code']],
                array_merge($loc, [
                    'last_heartbeat_at' => now(),
                    'capabilities' => ['http', 'https', 'api', 'tcp', 'dns', 'ssl', 'ping'],
                ])
            );
        }
        $this->line('  ✓ 8 Global Probe Locations active.');

        $this->info('Step 4/5: Seeding Subscription Plans...');
        $plans = [
            [
                'name' => 'Free Community',
                'slug' => 'free',
                'description' => 'Essential uptime monitoring for personal projects and indie developers.',
                'price_monthly' => 0.00,
                'price_yearly' => 0.00,
                'max_monitors' => 10,
                'min_check_interval_seconds' => 180,
                'max_status_pages' => 1,
                'max_users' => 1,
                'max_branches' => 1,
                'retention_days' => 14,
                'order_index' => 1,
            ],
            [
                'name' => 'Starter Pro',
                'slug' => 'starter',
                'description' => 'Fast 60-second checks, SSL warnings, and custom status pages.',
                'price_monthly' => 19.00,
                'price_yearly' => 190.00,
                'max_monitors' => 50,
                'min_check_interval_seconds' => 60,
                'max_status_pages' => 3,
                'max_users' => 5,
                'max_branches' => 2,
                'retention_days' => 60,
                'order_index' => 2,
                'is_featured' => true,
            ],
            [
                'name' => 'Professional Team',
                'slug' => 'professional',
                'description' => '30-second multi-region checks, REST API, on-call schedules, and AI Assistant.',
                'price_monthly' => 49.00,
                'price_yearly' => 490.00,
                'max_monitors' => 150,
                'min_check_interval_seconds' => 30,
                'max_status_pages' => 10,
                'max_users' => 15,
                'max_branches' => 5,
                'retention_days' => 180,
                'allow_api' => true,
                'allow_ai' => true,
                'order_index' => 3,
            ],
            [
                'name' => 'Business Agency',
                'slug' => 'business',
                'description' => 'White-label reporting, multi-client portals, and custom domain status pages.',
                'price_monthly' => 99.00,
                'price_yearly' => 990.00,
                'max_monitors' => 500,
                'min_check_interval_seconds' => 15,
                'max_status_pages' => 30,
                'max_users' => 50,
                'max_branches' => 15,
                'retention_days' => 365,
                'allow_api' => true,
                'allow_ai' => true,
                'allow_agency' => true,
                'allow_white_label' => true,
                'order_index' => 4,
            ],
            [
                'name' => 'Multinational Enterprise',
                'slug' => 'enterprise',
                'description' => 'Sub-second checks, private probe agents, SLA contracts, and custom integrations.',
                'price_monthly' => 299.00,
                'price_yearly' => 2990.00,
                'max_monitors' => 2500,
                'min_check_interval_seconds' => 10,
                'max_status_pages' => 100,
                'max_users' => 200,
                'max_branches' => 50,
                'retention_days' => 730,
                'allow_api' => true,
                'allow_ai' => true,
                'allow_agency' => true,
                'allow_white_label' => true,
                'order_index' => 5,
            ],
        ];

        foreach ($plans as $p) {
            SubscriptionPlan::updateOrCreate(['slug' => $p['slug']], $p);
        }
        $this->line('  ✓ 5 Subscription Plans configured.');

        $this->info('Step 5/5: Creating Platform Super Administrator...');
        $admin = User::firstOrCreate(
            ['email' => 'superadmin@demo.example'],
            [
                'name' => 'Imran Ahmed (Platform Admin)',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'is_platform_superadmin' => true,
                'is_active' => true,
            ]
        );
        $this->line("  ✓ Super Admin created: {$admin->email} (Password: password)");

        $this->info("\nInstallation complete! You can now access:");
        $this->line('  • Platform Super Admin: '.url('/platform'));
        $this->line('  • Tenant Owner Portal:  '.url('/app'));
        $this->line('  • Branch Operations:    '.url('/branch'));
        $this->line('  • Client Portal:        '.url('/client'));
        $this->line('  • SRE Operations:       '.url('/operations'));
        $this->line('  • Interactive Demo:     '.url('/demo'));

        return Command::SUCCESS;
    }
}
