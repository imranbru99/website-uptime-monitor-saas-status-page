<?php

namespace App\Domain\Monitoring\Models;

use App\Domain\Alerts\Models\AlertRule;
use App\Domain\Branches\Models\Team;
use App\Domain\Branches\Traits\BelongsToBranch;
use App\Domain\Incidents\Models\Incident;
use App\Domain\Tenancy\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Monitor extends Model
{
    use BelongsToBranch, BelongsToTenant, HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'team_id',
        'private_agent_id',
        'name',
        'description',
        'monitor_type',
        'target',
        'check_interval_seconds',
        'timeout_seconds',
        'retry_count',
        'confirmation_threshold',
        'status',
        'consecutive_failures',
        'consecutive_successes',
        'last_checked_at',
        'next_check_due_at',
        'last_status_change_at',
        'uptime_percentage_24h',
        'uptime_percentage_7d',
        'uptime_percentage_30d',
        'avg_latency_ms',
        'tags',
        'selected_locations',
        'config',
        'is_active',
        'is_paused',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'selected_locations' => 'array',
            'config' => 'array',
            'is_active' => 'boolean',
            'is_paused' => 'boolean',
            'last_checked_at' => 'datetime',
            'next_check_due_at' => 'datetime',
            'last_status_change_at' => 'datetime',
            'uptime_percentage_24h' => 'decimal:2',
            'uptime_percentage_7d' => 'decimal:2',
            'uptime_percentage_30d' => 'decimal:2',
            'avg_latency_ms' => 'decimal:2',
            'check_interval_seconds' => 'integer',
            'timeout_seconds' => 'integer',
            'retry_count' => 'integer',
            'confirmation_threshold' => 'integer',
            'consecutive_failures' => 'integer',
            'consecutive_successes' => 'integer',
        ];
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function privateAgent(): BelongsTo
    {
        return $this->belongsTo(MonitoringAgent::class, 'private_agent_id');
    }

    public function checks(): HasMany
    {
        return $this->hasMany(MonitorCheck::class);
    }

    public function latestCheck(): HasOne
    {
        return $this->hasOne(MonitorCheck::class)->latestOfMany('checked_at');
    }

    public function statusTransitions(): HasMany
    {
        return $this->hasMany(MonitorStatusTransition::class);
    }

    public function heartbeat(): HasOne
    {
        return $this->hasOne(Heartbeat::class);
    }

    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class);
    }

    public function alertRules(): HasMany
    {
        return $this->hasMany(AlertRule::class);
    }

    public function isHealthy(): bool
    {
        return $this->status === 'healthy';
    }

    public function isDown(): bool
    {
        return in_array($this->status, ['down', 'suspected_down']);
    }
}
