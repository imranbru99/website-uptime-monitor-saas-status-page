<?php

namespace App\Domain\Incidents\Services;

use App\Domain\Incidents\Models\Incident;
use App\Domain\Monitoring\Models\Monitor;

class CorrelationEngine
{
    /**
     * Find an active incident from the same branch/tenant in the last 10 minutes to correlate against.
     */
    public function findCorrelatedParent(Monitor $monitor): ?Incident
    {
        return Incident::where('tenant_id', $monitor->tenant_id)
            ->where('branch_id', $monitor->branch_id)
            ->whereNull('parent_incident_id')
            ->where('detected_at', '>=', now()->subMinutes(10))
            ->whereIn('status', ['detected', 'confirmed', 'acknowledged', 'investigating'])
            ->latest('detected_at')
            ->first();
    }
}
