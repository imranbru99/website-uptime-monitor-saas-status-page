<?php

namespace Tests\Feature;

use App\Domain\Monitoring\Models\Monitor;
use App\Domain\Monitoring\Models\MonitorCheck;
use App\Domain\Monitoring\Services\MonitoringStateMachine;
use App\Domain\Tenancy\Models\Tenant;
use Tests\TestCase;

class MonitorManagementTest extends TestCase
{
    public function test_state_machine_transitions_to_suspected_down_then_down(): void
    {
        $tenant = Tenant::create(['name' => 'Test Tenant', 'slug' => 'test-tenant']);

        $monitor = Monitor::create([
            'tenant_id' => $tenant->id,
            'name' => 'API Endpoint',
            'target' => 'https://api.example.com/status',
            'monitor_type' => 'https',
            'confirmation_threshold' => 2,
            'status' => 'healthy',
        ]);

        $stateMachine = app(MonitoringStateMachine::class);

        // First failure: threshold is 2, so should transition to suspected_down
        $check1 = MonitorCheck::create([
            'monitor_id' => $monitor->id,
            'tenant_id' => $tenant->id,
            'status' => 'failed',
            'error_message' => 'Connection timed out',
            'checked_at' => now(),
        ]);
        $stateMachine->processCheck($monitor, $check1);
        $this->assertEquals('suspected_down', $monitor->fresh()->status);

        // Second failure: reaches confirmation threshold 2, should transition to down
        $check2 = MonitorCheck::create([
            'monitor_id' => $monitor->id,
            'tenant_id' => $tenant->id,
            'status' => 'failed',
            'error_message' => 'Connection timed out again',
            'checked_at' => now(),
        ]);
        $stateMachine->processCheck($monitor, $check2);
        $this->assertEquals('down', $monitor->fresh()->status);

        // Recovery: success check observed, should transition to recovering then healthy
        $check3 = MonitorCheck::create([
            'monitor_id' => $monitor->id,
            'tenant_id' => $tenant->id,
            'status' => 'success',
            'latency_ms' => 35.0,
            'checked_at' => now(),
        ]);
        $stateMachine->processCheck($monitor, $check3);
        $this->assertEquals('recovering', $monitor->fresh()->status);
    }
}
