<?php

namespace Tests\Feature;

use App\Domain\Monitoring\Models\Heartbeat;
use App\Domain\Monitoring\Models\Monitor;
use App\Domain\Tenancy\Models\Tenant;
use App\Models\User;
use Tests\TestCase;

class ApiV1Test extends TestCase
{
    public function test_user_can_authenticate_and_list_monitors(): void
    {
        $tenant = Tenant::create(['name' => 'Nova Corp', 'slug' => 'nova-corp']);
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@novacorp.example',
            'password' => bcrypt('secret123'),
            'role' => 'owner',
            'is_active' => true,
        ]);
        $user->tenants()->attach($tenant->id, ['role' => 'owner', 'is_owner' => true]);
        $user->update(['current_tenant_id' => $tenant->id]);

        Monitor::create([
            'tenant_id' => $tenant->id,
            'name' => 'Production Edge',
            'target' => 'https://nova.example',
            'monitor_type' => 'https',
            'status' => 'healthy',
        ]);

        // 1. Get Token
        $tokenResponse = $this->postJson('/api/v1/auth/token', [
            'email' => 'john@novacorp.example',
            'password' => 'secret123',
        ]);

        $tokenResponse->assertStatus(200);
        $token = $tokenResponse->json('access_token');
        $this->assertNotEmpty($token);

        // 2. List Monitors
        $monitorsResponse = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/monitors');

        $monitorsResponse->assertStatus(200);
        $monitorsResponse->assertJsonFragment(['name' => 'Production Edge']);
    }

    public function test_heartbeat_ping_acknowledges_pulse(): void
    {
        $tenant = Tenant::create(['name' => 'Pulse Tech', 'slug' => 'pulse']);
        $monitor = Monitor::create([
            'tenant_id' => $tenant->id,
            'name' => 'Cron Sync Job',
            'target' => 'cron-job-1',
            'monitor_type' => 'heartbeat',
            'status' => 'pending',
        ]);

        $heartbeat = Heartbeat::create([
            'monitor_id' => $monitor->id,
            'tenant_id' => $tenant->id,
            'secret_token' => 'hb_secret_token_12345',
            'interval_seconds' => 300,
            'grace_seconds' => 60,
        ]);

        $response = $this->postJson("/api/v1/heartbeat/{$heartbeat->secret_token}");

        $response->assertStatus(200);
        $response->assertJson(['status' => 'ok', 'message' => 'Heartbeat pulse acknowledged.']);
        $this->assertNotNull($heartbeat->fresh()->last_ping_at);
        $this->assertEquals('healthy', $monitor->fresh()->status);
    }
}
