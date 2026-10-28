<?php

namespace Tests\Feature;

use App\Domain\Monitoring\Models\Monitor;
use App\Domain\Tenancy\Models\Tenant;
use App\Domain\Tenancy\TenantManager;
use App\Models\User;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    public function test_tenant_b_cannot_access_tenant_a_monitors(): void
    {
        $tenantA = Tenant::create(['name' => 'Tenant Alpha', 'slug' => 'tenant-alpha']);
        $tenantB = Tenant::create(['name' => 'Tenant Beta', 'slug' => 'tenant-beta']);

        $userA = User::create([
            'name' => 'Alpha Owner',
            'email' => 'alpha@example.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
            'is_active' => true,
        ]);
        $userA->tenants()->attach($tenantA->id, ['role' => 'owner', 'is_owner' => true]);
        $userA->update(['current_tenant_id' => $tenantA->id]);

        $userB = User::create([
            'name' => 'Beta Owner',
            'email' => 'beta@example.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
            'is_active' => true,
        ]);
        $userB->tenants()->attach($tenantB->id, ['role' => 'owner', 'is_owner' => true]);
        $userB->update(['current_tenant_id' => $tenantB->id]);

        $monitorA = Monitor::create([
            'tenant_id' => $tenantA->id,
            'name' => 'Alpha Confidential Target',
            'target' => 'https://alpha.internal',
            'monitor_type' => 'https',
            'status' => 'healthy',
        ]);

        // User B authenticates via Sanctum API
        $tokenB = $userB->createToken('test_token')->plainTextToken;

        // User B tries to view Tenant A's monitor by ID
        $response = $this->withHeader('Authorization', "Bearer {$tokenB}")
            ->getJson("/api/v1/monitors/{$monitorA->id}");

        // Must return 404 Not Found due to tenant scoping
        $response->assertStatus(404);

        // User B tries to list monitors - must not see Tenant A's monitor
        $listResponse = $this->withHeader('Authorization', "Bearer {$tokenB}")
            ->getJson('/api/v1/monitors');

        $listResponse->assertStatus(200);
        $listResponse->assertJsonMissing(['name' => 'Alpha Confidential Target']);

        // Check global Eloquent TenantScope directly
        TenantManager::setTenant($tenantB);
        $scopedMonitors = Monitor::all();
        $this->assertCount(0, $scopedMonitors);
        TenantManager::clear();
    }
}
