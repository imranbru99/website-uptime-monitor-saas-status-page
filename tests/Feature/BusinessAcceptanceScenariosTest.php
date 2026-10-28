<?php

namespace Tests\Feature;

use App\Domain\Billing\Models\SubscriptionPlan;
use App\Domain\Billing\Services\BillingManager;
use App\Domain\Branches\Models\Branch;
use App\Domain\Incidents\Models\Incident;
use App\Domain\Modules\Services\ModuleManager;
use App\Domain\Modules\Services\ModuleRegistry;
use App\Domain\Monitoring\Models\Monitor;
use App\Domain\Monitoring\Models\MonitorCheck;
use App\Domain\Monitoring\Services\MonitoringStateMachine;
use App\Domain\StatusPages\Models\StatusPage;
use App\Domain\StatusPages\Models\StatusPageComponent;
use App\Domain\Tenancy\Models\Tenant;
use App\Models\User;
use Tests\TestCase;

class BusinessAcceptanceScenariosTest extends TestCase
{
    /**
     * Scenario A — Freelancer Journey: signs up, creates monitor, reviews status.
     */
    public function test_scenario_a_freelancer_journey(): void
    {
        $response = $this->post('/onboarding', [
            'business_name' => 'Solo Freelance Studio',
            'business_mode' => 'freelancer',
            'email' => 'freelancer@example.com',
            'password' => 'secret12345',
            'monitor_name' => 'Portfolio Site',
            'target_url' => 'https://freelancer-portfolio.example',
            'plan_id' => SubscriptionPlan::firstOrCreate(['slug' => 'starter'], [
                'name' => 'Starter Pro',
                'max_monitors' => 50,
                'min_check_interval_seconds' => 60,
                'price_monthly' => 19.00,
            ])->id,
        ]);

        $response->assertRedirect('/app');
        $this->assertDatabaseHas('tenants', ['name' => 'Solo Freelance Studio']);
        $this->assertDatabaseHas('monitors', ['name' => 'Portfolio Site']);
        $this->assertDatabaseHas('users', ['email' => 'freelancer@example.com']);
    }

    /**
     * Scenario B — Ecommerce Store: Storefront, API, and Public Status Page.
     */
    public function test_scenario_b_ecommerce_business_monitoring(): void
    {
        $tenant = Tenant::create(['name' => 'UrbanWear Store', 'slug' => 'urbanwear', 'business_mode' => 'ecommerce']);

        $storeMonitor = Monitor::create([
            'tenant_id' => $tenant->id,
            'name' => 'Storefront Web App',
            'target' => 'https://urbanwear.example',
            'monitor_type' => 'https',
            'status' => 'healthy',
        ]);

        $checkoutMonitor = Monitor::create([
            'tenant_id' => $tenant->id,
            'name' => 'Checkout API Sandbox',
            'target' => 'https://checkout.urbanwear.example',
            'monitor_type' => 'api',
            'status' => 'healthy',
        ]);

        $statusPage = StatusPage::create([
            'tenant_id' => $tenant->id,
            'title' => 'UrbanWear Live Status',
            'slug' => 'urbanwear-status',
            'visibility' => 'public',
            'is_active' => true,
        ]);

        StatusPageComponent::create([
            'status_page_id' => $statusPage->id,
            'monitor_id' => $storeMonitor->id,
            'name' => 'Main Storefront',
            'status' => 'operational',
        ]);

        $this->assertEquals('operational', $statusPage->computed_status);

        $response = $this->get("/status/{$statusPage->slug}");
        $response->assertStatus(200);
        $response->assertSee('UrbanWear Live Status');
    }

    /**
     * Scenario C — Multi-Branch Enterprise Scoping.
     */
    public function test_scenario_c_multi_branch_scoping(): void
    {
        $tenant = Tenant::create(['name' => 'Global IT Corp', 'slug' => 'global-it']);

        $branchAsia = Branch::create(['tenant_id' => $tenant->id, 'name' => 'Asia Branch', 'code' => 'AS-01']);
        $branchEurope = Branch::create(['tenant_id' => $tenant->id, 'name' => 'Europe Branch', 'code' => 'EU-01']);

        $monitorAsia = Monitor::create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branchAsia->id,
            'name' => 'Tokyo Edge Node',
            'target' => 'https://tokyo.globalit.example',
            'monitor_type' => 'https',
            'status' => 'healthy',
        ]);

        $monitorEurope = Monitor::create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branchEurope->id,
            'name' => 'Frankfurt Core Gateway',
            'target' => 'https://frankfurt.globalit.example',
            'monitor_type' => 'https',
            'status' => 'healthy',
        ]);

        // Branch Asia only sees its monitor
        $this->assertEquals(1, $branchAsia->monitors()->count());
        $this->assertEquals('Tokyo Edge Node', $branchAsia->monitors()->first()->name);

        // Headquarters sees consolidated monitors
        $this->assertEquals(2, $tenant->monitors()->count());
    }

    /**
     * Scenario E — Incident Lifecycle: failure confirmation -> acknowledge -> recovery -> resolution.
     */
    public function test_scenario_e_incident_lifecycle_end_to_end(): void
    {
        $tenant = Tenant::create(['name' => 'FinTech Engine', 'slug' => 'fintech-engine']);
        $engineer = User::create([
            'name' => 'SRE OnCall',
            'email' => 'sre@fintech.example',
            'password' => bcrypt('password'),
            'role' => 'engineer',
        ]);

        $monitor = Monitor::create([
            'tenant_id' => $tenant->id,
            'name' => 'Payment Settlement Node',
            'target' => 'https://settlement.fintech.example',
            'monitor_type' => 'api',
            'confirmation_threshold' => 1,
            'status' => 'healthy',
        ]);

        $stateMachine = app(MonitoringStateMachine::class);

        // 1. Probe check fails
        $failureCheck = MonitorCheck::create([
            'monitor_id' => $monitor->id,
            'tenant_id' => $tenant->id,
            'status' => 'failed',
            'error_message' => '503 Service Unavailable',
            'status_code' => 503,
            'checked_at' => now(),
        ]);
        $stateMachine->processCheck($monitor, $failureCheck);

        $this->assertEquals('down', $monitor->fresh()->status);

        // 2. Automated incident created
        $incident = Incident::where('monitor_id', $monitor->id)->first();
        $this->assertNotNull($incident);
        $this->assertEquals('confirmed', $incident->status);

        // 3. Engineer acknowledges incident
        $incident->acknowledge($engineer);
        $this->assertEquals('acknowledged', $incident->fresh()->status);
        $this->assertEquals($engineer->id, $incident->fresh()->acknowledged_by_id);

        // 4. Probe verifies recovery
        $recoveryCheck = MonitorCheck::create([
            'monitor_id' => $monitor->id,
            'tenant_id' => $tenant->id,
            'status' => 'success',
            'latency_ms' => 22.4,
            'status_code' => 200,
            'checked_at' => now(),
        ]);
        $stateMachine->processCheck($monitor, $recoveryCheck);

        // Confirmation check transitions from 'recovering' to 'healthy' and auto-resolves incident
        $confirmCheck = MonitorCheck::create([
            'monitor_id' => $monitor->id,
            'tenant_id' => $tenant->id,
            'status' => 'success',
            'latency_ms' => 20.8,
            'status_code' => 200,
            'checked_at' => now()->addSeconds(60),
        ]);
        $stateMachine->processCheck($monitor, $confirmCheck);

        // 5. Incident auto-resolved
        $this->assertEquals('resolved', $incident->fresh()->status);
        $this->assertNotNull($incident->fresh()->resolved_at);
    }

    /**
     * Scenario F — Dynamic Module Customization without data loss.
     */
    public function test_scenario_f_module_toggle_data_preservation(): void
    {
        ModuleRegistry::syncModulesToDatabase();

        $tenant = Tenant::create(['name' => 'Modular Tech', 'slug' => 'modular-tech']);

        $monitor = Monitor::create([
            'tenant_id' => $tenant->id,
            'name' => 'Domain Expiration Target',
            'target' => 'modular-tech.example',
            'monitor_type' => 'domain',
            'status' => 'healthy',
        ]);

        // Disable domain monitoring
        ModuleManager::disable('domain_expiration', $tenant);
        $this->assertFalse(ModuleManager::isEnabled('domain_expiration', $tenant));

        // Re-enable domain monitoring
        ModuleManager::enable('domain_expiration', $tenant);
        $this->assertTrue(ModuleManager::isEnabled('domain_expiration', $tenant));

        // Check that monitor data was completely preserved
        $this->assertDatabaseHas('monitors', [
            'id' => $monitor->id,
            'name' => 'Domain Expiration Target',
        ]);
    }

    /**
     * Scenario G — Subscription plan upgrade & quota enforcement.
     */
    public function test_scenario_g_subscription_quota_enforcement(): void
    {
        $freePlan = SubscriptionPlan::create([
            'name' => 'Free Tier',
            'slug' => 'free-tier',
            'max_monitors' => 1,
            'price_monthly' => 0.00,
        ]);

        $proPlan = SubscriptionPlan::create([
            'name' => 'Pro Tier',
            'slug' => 'pro-tier',
            'max_monitors' => 10,
            'price_monthly' => 49.00,
        ]);

        $tenant = Tenant::create([
            'name' => 'Growth SaaS',
            'slug' => 'growth-saas',
            'subscription_plan_id' => $freePlan->id,
        ]);

        // Add 1 monitor
        Monitor::create([
            'tenant_id' => $tenant->id,
            'name' => 'First Monitor',
            'target' => 'https://m1.example',
            'monitor_type' => 'https',
        ]);

        // Under Free tier (max_monitors = 1), tenant cannot add more monitors
        $this->assertFalse($tenant->fresh()->canAddMonitor());

        // Upgrade to Pro tier
        app(BillingManager::class)->subscribe($tenant, $proPlan);

        // Now tenant can add more monitors
        $this->assertTrue($tenant->fresh()->canAddMonitor());
    }
}
