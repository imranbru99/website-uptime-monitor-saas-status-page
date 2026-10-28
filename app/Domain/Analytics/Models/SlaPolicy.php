<?php

namespace App\Domain\Analytics\Models;

use App\Domain\Branches\Traits\BelongsToBranch;
use App\Domain\Monitoring\Models\Monitor;
use App\Domain\Tenancy\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SlaPolicy extends Model
{
    use BelongsToBranch, BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'monitor_id',
        'name',
        'target_uptime_percentage',
        'measurement_window',
        'exclude_maintenance',
        'error_budget_alert_threshold',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'target_uptime_percentage' => 'decimal:2',
            'error_budget_alert_threshold' => 'decimal:2',
            'exclude_maintenance' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(Monitor::class);
    }

    public function complianceRecords(): HasMany
    {
        return $this->hasMany(SlaComplianceRecord::class);
    }
}
