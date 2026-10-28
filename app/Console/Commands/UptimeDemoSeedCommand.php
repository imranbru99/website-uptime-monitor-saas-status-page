<?php

namespace App\Console\Commands;

use App\Domain\Alerts\Models\NotificationChannel;
use App\Domain\Analytics\Models\SlaPolicy;
use App\Domain\Billing\Models\SubscriptionPlan;
use App\Domain\Branches\Models\Branch;
use App\Domain\Incidents\Models\Incident;
use App\Domain\Monitoring\Models\Heartbeat;
use App\Domain\Monitoring\Models\Monitor;
use App\Domain\Monitoring\Models\MonitorCheck;
use App\Domain\Monitoring\Models\MonitorLocation;
use App\Domain\StatusPages\Models\StatusPage;
use App\Domain\StatusPages\Models\StatusPageComponent;
use App\Domain\Tenancy\Models\Tenant;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class UptimeDemoSeedCommand extends Command
{
    protected $signature = 'uptime:demo-seed';

    protected $description = 'Seed comprehensive, realistic demonstration dataset across 4 enterprise business tenants';

    public function handle(): int
    {
        $this->info('========================================================');
        $this->info('  Seeding Realistic Demonstration Dataset & Tenants');
        $this->info('  Author: Imran Ahmed <me@imrandev.bd>');
        $this->info('========================================================');

        $proPlan = SubscriptionPlan::where('slug', 'professional')->first();
        $bizPlan = SubscriptionPlan::where('slug', 'business')->first();
        $entPlan = SubscriptionPlan::where('slug', 'enterprise')->first();
        $sinLoc = MonitorLocation::where('code', 'sin')->first();
        $tyoLoc = MonitorLocation::where('code', 'tyo')->first();
        $lhrLoc = MonitorLocation::where('code', 'lhr')->first();
        $nycLoc = MonitorLocation::where('code', 'nyc')->first();

        // 1. Create Demo Users (9 Documented Roles)
        $users = [
            'owner@demo.example' => ['name' => 'Sarah Connor (Tenant Owner)', 'role' => 'owner'],
            'admin@demo.example' => ['name' => 'Alex Murphy (Tenant Admin)', 'role' => 'admin'],
            'branch.manager@demo.example' => ['name' => 'Tariq Vance (Branch Manager)', 'role' => 'branch_manager'],
            'engineer@demo.example' => ['name' => 'Elena Rostova (SRE Lead)', 'role' => 'engineer'],
            'analyst@demo.example' => ['name' => 'David Kim (Reliability Analyst)', 'role' => 'analyst'],
            'client@demo.example' => ['name' => 'Marcus Brody (Agency Client)', 'role' => 'client'],
            'billing@demo.example' => ['name' => 'Rachel Green (Billing Specialist)', 'role' => 'billing'],
            'support@demo.example' => ['name' => 'James Holden (Support Agent)', 'role' => 'support'],
        ];

        $userInstances = [];
        foreach ($users as $email => $data) {
            $userInstances[$email] = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'role' => $data['role'],
                    'is_active' => true,
                ]
            );
        }
        $this->line('  ✓ 9 Seeded Demonstration Accounts configured.');

        // ==========================================
        // DEMO A: NimbusCloud Technologies (SaaS)
        // ==========================================
        $this->info('Seeding Demo A: NimbusCloud Technologies (SaaS Company)...');
        $nimbus = Tenant::updateOrCreate(
            ['slug' => 'nimbuscloud'],
            [
                'name' => 'NimbusCloud Technologies',
                'business_mode' => 'saas',
                'email' => 'ops@nimbuscloud.example',
                'country' => 'SG',
                'timezone' => 'Asia/Singapore',
                'currency' => 'USD',
                'subscription_plan_id' => $proPlan?->id,
                'subscription_status' => 'active',
                'retention_days' => 180,
                'is_active' => true,
            ]
        );

        $nimbus->users()->syncWithoutDetaching([
            $userInstances['owner@demo.example']->id => ['role' => 'owner', 'is_owner' => true],
            $userInstances['engineer@demo.example']->id => ['role' => 'engineer', 'is_owner' => false],
        ]);
        $userInstances['owner@demo.example']->update(['current_tenant_id' => $nimbus->id]);

        $nimbusSin = Branch::updateOrCreate(
            ['tenant_id' => $nimbus->id, 'code' => 'SIN-HQ'],
            ['name' => 'Singapore Headquarters', 'country' => 'SG', 'timezone' => 'Asia/Singapore']
        );
        $nimbusLdn = Branch::updateOrCreate(
            ['tenant_id' => $nimbus->id, 'code' => 'LDN-OPS'],
            ['name' => 'London Operations', 'country' => 'GB', 'timezone' => 'Europe/London']
        );
        $nimbusNyc = Branch::updateOrCreate(
            ['tenant_id' => $nimbus->id, 'code' => 'NYC-INFRA'],
            ['name' => 'New York Infrastructure', 'country' => 'US', 'timezone' => 'America/New_York']
        );

        $nimbusMonitors = [
            ['name' => 'Main SaaS Web Portal', 'target' => 'https://nimbuscloud.example', 'type' => 'https', 'status' => 'healthy', 'lat' => 28.4],
            ['name' => 'OAuth2 & Auth Gateway API', 'target' => 'https://auth.nimbuscloud.example/v1', 'type' => 'api', 'status' => 'healthy', 'lat' => 19.2],
            ['name' => 'Billing & Stripe Microservice', 'target' => 'https://billing.nimbuscloud.example', 'type' => 'api', 'status' => 'healthy', 'lat' => 44.1],
            ['name' => 'PostgreSQL Master Database', 'target' => 'postgres.nimbuscloud.example:5432', 'type' => 'tcp', 'status' => 'healthy', 'lat' => 8.5],
            ['name' => 'Redis Primary Cache Node', 'target' => 'cache.nimbuscloud.example:6379', 'type' => 'tcp', 'status' => 'healthy', 'lat' => 2.1],
            ['name' => 'SSL Certificate Expiration', 'target' => 'nimbuscloud.example', 'type' => 'ssl', 'status' => 'healthy', 'lat' => 15.0],
            ['name' => 'Authoritative DNS Resolution', 'target' => 'nimbuscloud.example', 'type' => 'dns', 'status' => 'healthy', 'lat' => 12.0],
            ['name' => 'Nightly Backup Heartbeat', 'target' => 'backup-job-nimbus', 'type' => 'heartbeat', 'status' => 'healthy', 'lat' => 0.0],
        ];

        $nimbusMonitorModels = [];
        foreach ($nimbusMonitors as $mDef) {
            $m = Monitor::updateOrCreate(
                ['tenant_id' => $nimbus->id, 'name' => $mDef['name']],
                [
                    'branch_id' => $nimbusSin->id,
                    'target' => $mDef['target'],
                    'monitor_type' => $mDef['type'],
                    'check_interval_seconds' => 30,
                    'timeout_seconds' => 10,
                    'confirmation_threshold' => 2,
                    'status' => $mDef['status'],
                    'uptime_percentage_24h' => 100.00,
                    'uptime_percentage_7d' => 99.98,
                    'uptime_percentage_30d' => 99.95,
                    'avg_latency_ms' => $mDef['lat'],
                    'last_checked_at' => now(),
                    'next_check_due_at' => now()->addSeconds(30),
                    'is_active' => true,
                ]
            );
            $nimbusMonitorModels[] = $m;

            if ($mDef['type'] === 'heartbeat') {
                Heartbeat::updateOrCreate(
                    ['monitor_id' => $m->id],
                    [
                        'tenant_id' => $nimbus->id,
                        'interval_seconds' => 86400,
                        'grace_seconds' => 3600,
                        'last_ping_at' => now()->subHours(2),
                        'next_expected_at' => now()->addHours(22),
                        'status' => 'healthy',
                    ]
                );
            }

            // Seed historical telemetry
            $this->seedHistoricalChecks($m, $sinLoc);
        }

        // Nimbus Status Page
        $nimbusStatus = StatusPage::updateOrCreate(
            ['slug' => 'nimbuscloud-status'],
            [
                'tenant_id' => $nimbus->id,
                'branch_id' => $nimbusSin->id,
                'title' => 'NimbusCloud Service Status',
                'description' => 'Real-time telemetry and incident reports across global clusters.',
                'visibility' => 'public',
                'primary_color' => '#2563EB',
                'show_uptime_history' => true,
                'show_incident_history' => true,
                'is_active' => true,
            ]
        );

        foreach (array_slice($nimbusMonitorModels, 0, 4) as $idx => $m) {
            StatusPageComponent::updateOrCreate(
                ['status_page_id' => $nimbusStatus->id, 'name' => $m->name],
                [
                    'monitor_id' => $m->id,
                    'group_name' => 'Core Services',
                    'order_index' => $idx,
                    'status' => 'operational',
                ]
            );
        }

        // Seed Sample Incident for Nimbus
        $nimbusIncident = Incident::updateOrCreate(
            ['tenant_id' => $nimbus->id, 'title' => 'Transient Network Degradation in Tokyo Peering'],
            [
                'branch_id' => $nimbusSin->id,
                'monitor_id' => $nimbusMonitorModels[0]->id,
                'description' => 'Upstream ISP transit jitter observed from Tokyo node.',
                'severity' => 'medium',
                'status' => 'resolved',
                'detected_at' => now()->subDays(2)->subHours(3),
                'acknowledged_at' => now()->subDays(2)->subHours(3)->addMinutes(4),
                'acknowledged_by_id' => $userInstances['engineer@demo.example']->id,
                'resolved_at' => now()->subDays(2)->subHours(2),
                'resolved_by_id' => $userInstances['engineer@demo.example']->id,
                'resolution_summary' => 'Upstream Tier-1 carrier rerouted BGP routes. Handshake latency normalized.',
            ]
        );

        $nimbusIncident->updates()->createMany([
            ['status' => 'investigating', 'message' => 'Automated probe alert: latency spike detected.', 'is_public' => true, 'posted_at' => now()->subDays(2)->subHours(3)],
            ['status' => 'resolved', 'message' => 'Traffic rerouted. Latency back to baseline 28ms.', 'is_public' => true, 'posted_at' => now()->subDays(2)->subHours(2)],
        ]);

        // SLA Policy
        SlaPolicy::updateOrCreate(
            ['tenant_id' => $nimbus->id, 'name' => 'Monthly Production SLA (99.95%)'],
            [
                'target_uptime_percentage' => 99.95,
                'measurement_window' => 'monthly',
                'exclude_maintenance' => true,
                'error_budget_alert_threshold' => 80.00,
                'is_active' => true,
            ]
        );

        // Notification Channels
        NotificationChannel::updateOrCreate(
            ['tenant_id' => $nimbus->id, 'name' => 'Engineering Slack Alert Channel'],
            [
                'channel_type' => 'slack',
                'destination' => 'https://hooks.slack.com/services/T00/B00/DEMO_NIMBUS',
                'is_active' => true,
            ]
        );

        // ==========================================
        // DEMO B: ShopVista Commerce (Ecommerce)
        // ==========================================
        $this->info('Seeding Demo B: ShopVista Commerce (Ecommerce Business)...');
        $shopvista = Tenant::updateOrCreate(
            ['slug' => 'shopvista'],
            [
                'name' => 'ShopVista Commerce',
                'business_mode' => 'ecommerce',
                'email' => 'admin@shopvista.example',
                'country' => 'BD',
                'timezone' => 'Asia/Dhaka',
                'currency' => 'USD',
                'subscription_plan_id' => $proPlan?->id,
                'subscription_status' => 'active',
                'is_active' => true,
            ]
        );
        $shopvista->users()->syncWithoutDetaching([
            $userInstances['admin@demo.example']->id => ['role' => 'admin', 'is_owner' => true],
            $userInstances['billing@demo.example']->id => ['role' => 'billing', 'is_owner' => false],
        ]);

        $shopMonitors = [
            ['name' => 'Online Storefront & Catalog', 'target' => 'https://shopvista.example', 'type' => 'https', 'status' => 'healthy', 'lat' => 34.0],
            ['name' => 'Checkout Sandbox & Payment Funnel', 'target' => 'https://checkout.shopvista.example/pay', 'type' => 'api', 'status' => 'healthy', 'lat' => 52.0],
            ['name' => 'Stripe Webhook Listener', 'target' => 'https://api.shopvista.example/webhook/stripe', 'type' => 'api', 'status' => 'healthy', 'lat' => 28.0],
            ['name' => 'Domain Registration (shopvista.example)', 'target' => 'shopvista.example', 'type' => 'domain', 'status' => 'healthy', 'lat' => 0.0],
            ['name' => 'SSL Wildcard Certificate (*.shopvista.example)', 'target' => 'shopvista.example', 'type' => 'ssl', 'status' => 'healthy', 'lat' => 0.0],
        ];

        foreach ($shopMonitors as $mDef) {
            $m = Monitor::updateOrCreate(
                ['tenant_id' => $shopvista->id, 'name' => $mDef['name']],
                [
                    'target' => $mDef['target'],
                    'monitor_type' => $mDef['type'],
                    'check_interval_seconds' => 60,
                    'status' => $mDef['status'],
                    'uptime_percentage_24h' => 99.98,
                    'uptime_percentage_30d' => 99.92,
                    'avg_latency_ms' => $mDef['lat'],
                    'last_checked_at' => now(),
                    'next_check_due_at' => now()->addSeconds(60),
                    'is_active' => true,
                ]
            );
            $this->seedHistoricalChecks($m, $sinLoc);
        }

        // ShopVista Status Page
        StatusPage::updateOrCreate(
            ['slug' => 'shopvista-status'],
            [
                'tenant_id' => $shopvista->id,
                'title' => 'ShopVista Store Availability',
                'description' => 'Real-time uptime status for shoppers, sellers, and fulfillment partners.',
                'visibility' => 'public',
                'primary_color' => '#059669',
                'show_uptime_history' => true,
                'is_active' => true,
            ]
        );

        // ==========================================
        // DEMO C: PixelForge Digital Agency (Agency)
        // ==========================================
        $this->info('Seeding Demo C: PixelForge Digital Agency (Agency & Client Portals)...');
        $pixelforge = Tenant::updateOrCreate(
            ['slug' => 'pixelforge'],
            [
                'name' => 'PixelForge Digital Agency',
                'business_mode' => 'agency',
                'email' => 'agency@pixelforge.example',
                'country' => 'GB',
                'timezone' => 'Europe/London',
                'currency' => 'GBP',
                'subscription_plan_id' => $bizPlan?->id,
                'subscription_status' => 'active',
                'is_active' => true,
            ]
        );
        $pixelforge->users()->syncWithoutDetaching([
            $userInstances['branch.manager@demo.example']->id => ['role' => 'admin', 'is_owner' => true],
            $userInstances['client@demo.example']->id => ['role' => 'client', 'is_owner' => false],
        ]);
        $userInstances['client@demo.example']->update(['current_tenant_id' => $pixelforge->id]);

        $clientSites = [
            ['Apex Medical Clinic', 'https://apexclinic.example'],
            ['Metro Logistics International', 'https://metrologistics.example'],
            ['Aurora FinTech Platform', 'https://aurorafin.example'],
            ['Nordic Furnishings E-Store', 'https://nordicfurnishings.example'],
            ['Velocity Sports Club', 'https://velocitysports.example'],
        ];

        foreach ($clientSites as [$name, $url]) {
            $m = Monitor::updateOrCreate(
                ['tenant_id' => $pixelforge->id, 'name' => "Client: {$name}"],
                [
                    'target' => $url,
                    'monitor_type' => 'https',
                    'check_interval_seconds' => 60,
                    'status' => 'healthy',
                    'uptime_percentage_24h' => 100.00,
                    'uptime_percentage_30d' => 99.99,
                    'avg_latency_ms' => 24.5,
                    'last_checked_at' => now(),
                    'is_active' => true,
                ]
            );
            $this->seedHistoricalChecks($m, $lhrLoc);
        }

        // ==========================================
        // DEMO D: Atlas Enterprise Systems (Enterprise IT)
        // ==========================================
        $this->info('Seeding Demo D: Atlas Enterprise Systems (Enterprise IT & SLA)...');
        $atlas = Tenant::updateOrCreate(
            ['slug' => 'atlas'],
            [
                'name' => 'Atlas Enterprise Systems',
                'business_mode' => 'enterprise',
                'email' => 'enterprise-it@atlas.example',
                'country' => 'US',
                'timezone' => 'America/New_York',
                'currency' => 'USD',
                'subscription_plan_id' => $entPlan?->id,
                'subscription_status' => 'active',
                'retention_days' => 730,
                'is_active' => true,
            ]
        );
        $atlas->users()->syncWithoutDetaching([
            $userInstances['analyst@demo.example']->id => ['role' => 'analyst', 'is_owner' => false],
            $userInstances['support@demo.example']->id => ['role' => 'support', 'is_owner' => false],
        ]);

        $enterpriseServices = [
            ['Global SAP / ERP Gateway', 'https://erp.atlasenterprise.example', 'https', 42.0],
            ['Active Directory & LDAP Service', 'ldap.atlasenterprise.example:389', 'tcp', 11.0],
            ['Enterprise Data Warehouse DB', 'postgres.atlasenterprise.example:5432', 'database', 6.5],
            ['Regional VPC Edge Gateway', 'gateway.atlasenterprise.example:8080', 'tcp', 21.0],
            ['Internal Microservices Mesh', 'https://api-internal.atlasenterprise.example', 'api', 18.0],
        ];

        foreach ($enterpriseServices as [$name, $target, $type, $lat]) {
            $m = Monitor::updateOrCreate(
                ['tenant_id' => $atlas->id, 'name' => $name],
                [
                    'target' => $target,
                    'monitor_type' => $type,
                    'check_interval_seconds' => 15,
                    'status' => 'healthy',
                    'uptime_percentage_24h' => 100.00,
                    'uptime_percentage_30d' => 99.99,
                    'avg_latency_ms' => $lat,
                    'last_checked_at' => now(),
                    'is_active' => true,
                ]
            );
            $this->seedHistoricalChecks($m, $nycLoc);
        }

        $this->info("\nDemonstration seeding completed successfully!");
        $this->line('  • 4 Enterprise Business Tenants initialized.');
        $this->line('  • 100+ Check observations and incident timelines generated.');
        $this->line('  • Public interactive demo ready at '.url('/demo'));

        return Command::SUCCESS;
    }

    /**
     * Generate synthetic historical check results for charts and SLA calculations.
     */
    protected function seedHistoricalChecks(Monitor $monitor, ?MonitorLocation $location): void
    {
        // Avoid re-seeding if checks already exist
        if ($monitor->checks()->count() >= 20) {
            return;
        }

        $baseLatency = (float) ($monitor->avg_latency_ms ?: 30.0);
        $checks = [];

        for ($i = 0; $i < 24; $i++) {
            $jitter = rand(-5, 8);
            $lat = max(5.0, round($baseLatency + $jitter, 2));

            $checks[] = [
                'monitor_id' => $monitor->id,
                'tenant_id' => $monitor->tenant_id,
                'branch_id' => $monitor->branch_id,
                'location_id' => $location?->id,
                'status' => 'success',
                'status_code' => 200,
                'latency_ms' => $lat,
                'dns_time_ms' => round($lat * 0.2, 2),
                'tcp_time_ms' => round($lat * 0.3, 2),
                'tls_time_ms' => round($lat * 0.4, 2),
                'checked_at' => now()->subHours($i)->subMinutes(rand(1, 40)),
            ];
        }

        MonitorCheck::insert($checks);
    }
}
