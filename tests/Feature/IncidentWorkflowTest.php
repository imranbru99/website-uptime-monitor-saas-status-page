<?php

namespace Tests\Feature;

use App\Domain\Incidents\Models\Incident;
use App\Domain\Incidents\Services\IncidentManager;
use App\Domain\Monitoring\Models\Monitor;
use App\Domain\Monitoring\Models\MonitorCheck;
use App\Domain\Tenancy\Models\Tenant;
use App\Models\User;
use Tests\TestCase;

class IncidentWorkflowTest extends TestCase
{
    public function test_incident_is_automatically_created_and_resolved(): void
    {
        $tenant = Tenant::create(['name' => 'Cyber Systems', 'slug' => 'cyber-systems']);
        $user = User::create([
            'name' => 'Dev Engineer',
            'email' => 'dev@example.com',
            'password' => bcrypt('password'),
            'role' => 'engineer',
        ]);

        $monitor = Monitor::create([
            'tenant_id' => $tenant->id,
            'name' => 'Database Service',
            'target' => 'db.internal:5432',
            'monitor_type' => 'tcp',
            'status' => 'down',
        ]);

        $checkFail = MonitorCheck::create([
            'monitor_id' => $monitor->id,
            'tenant_id' => $tenant->id,
            'status' => 'failed',
            'error_message' => 'TCP connection refused',
            'error_type' => 'tcp_connection_failed',
            'checked_at' => now(),
        ]);

        $incidentManager = app(IncidentManager::class);

        // Auto-create incident
        $incident = $incidentManager->handleMonitorDown($monitor, $checkFail);
        $this->assertNotNull($incident);
        $this->assertEquals('confirmed', $incident->status);
        $this->assertEquals($monitor->id, $incident->monitor_id);

        // Acknowledge incident
        $incident->acknowledge($user);
        $this->assertEquals('acknowledged', $incident->fresh()->status);
        $this->assertEquals($user->id, $incident->fresh()->acknowledged_by_id);

        // Auto-resolve incident upon probe recovery
        $checkSuccess = MonitorCheck::create([
            'monitor_id' => $monitor->id,
            'tenant_id' => $tenant->id,
            'status' => 'success',
            'latency_ms' => 12.0,
            'checked_at' => now(),
        ]);

        $incidentManager->handleMonitorRecovered($monitor, $checkSuccess);
        $this->assertEquals('resolved', $incident->fresh()->status);
        $this->assertNotNull($incident->fresh()->resolved_at);
    }
}
