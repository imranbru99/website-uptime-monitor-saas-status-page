<?php

namespace App\Domain\Analytics\Models;

use App\Domain\Monitoring\Models\Monitor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SlaComplianceRecord extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'sla_policy_id',
        'monitor_id',
        'period_start',
        'period_end',
        'target_uptime_percentage',
        'measured_uptime_percentage',
        'total_minutes',
        'downtime_minutes',
        'error_budget_minutes',
        'error_budget_consumed_minutes',
        'is_breached',
        'calculated_at',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'target_uptime_percentage' => 'decimal:2',
            'measured_uptime_percentage' => 'decimal:2',
            'total_minutes' => 'integer',
            'downtime_minutes' => 'integer',
            'error_budget_minutes' => 'integer',
            'error_budget_consumed_minutes' => 'integer',
            'is_breached' => 'boolean',
            'calculated_at' => 'datetime',
        ];
    }

    public function slaPolicy(): BelongsTo
    {
        return $this->belongsTo(SlaPolicy::class);
    }

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(Monitor::class);
    }
}
