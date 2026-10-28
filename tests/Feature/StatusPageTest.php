<?php

namespace Tests\Feature;

use App\Domain\Monitoring\Models\Monitor;
use App\Domain\StatusPages\Models\StatusPage;
use App\Domain\StatusPages\Models\StatusPageComponent;
use App\Domain\Tenancy\Models\Tenant;
use Tests\TestCase;

class StatusPageTest extends TestCase
{
    public function test_it_computes_overall_status_and_renders_page(): void
    {
        $tenant = Tenant::create(['name' => 'Solaris Cloud', 'slug' => 'solaris']);

        $monitor = Monitor::create([
            'tenant_id' => $tenant->id,
            'name' => 'API Node',
            'target' => 'https://api.solaris.example',
            'monitor_type' => 'https',
            'status' => 'healthy',
        ]);

        $page = StatusPage::create([
            'tenant_id' => $tenant->id,
            'title' => 'Solaris Cloud Status',
            'slug' => 'solaris-status',
            'visibility' => 'public',
            'is_active' => true,
        ]);

        StatusPageComponent::create([
            'status_page_id' => $page->id,
            'monitor_id' => $monitor->id,
            'name' => 'API Gateway',
            'status' => 'operational',
        ]);

        $this->assertEquals('operational', $page->computed_status);

        $response = $this->get("/status/{$page->slug}");
        $response->assertStatus(200);
        $response->assertSee('Solaris Cloud Status');
        $response->assertSee('All Systems Operational');
    }

    public function test_visitor_can_subscribe_to_status_updates(): void
    {
        $tenant = Tenant::create(['name' => 'Delta Tech', 'slug' => 'delta']);
        $page = StatusPage::create([
            'tenant_id' => $tenant->id,
            'title' => 'Delta System Status',
            'slug' => 'delta-status',
            'visibility' => 'public',
            'is_active' => true,
        ]);

        $response = $this->post("/status/{$page->slug}/subscribe", [
            'destination' => 'subscriber@example.com',
            'subscription_type' => 'email',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('status_page_subscribers', [
            'status_page_id' => $page->id,
            'destination' => 'subscriber@example.com',
        ]);
    }
}
