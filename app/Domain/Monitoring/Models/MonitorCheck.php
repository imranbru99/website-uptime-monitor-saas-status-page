<?php

namespace App\Domain\Monitoring\Models;

use App\Domain\Branches\Models\Branch;
use App\Domain\Tenancy\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitorCheck extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'monitor_id',
        'tenant_id',
        'branch_id',
        'location_id',
        'agent_id',
        'status',
        'status_code',
        'latency_ms',
        'dns_time_ms',
        'tcp_time_ms',
        'tls_time_ms',
        'error_message',
        'error_type',
        'response_headers',
        'response_snippet',
        'checked_at',
    ];

    protected function casts(): array
    {
        return [
            'latency_ms' => 'decimal:2',
            'dns_time_ms' => 'decimal:2',
            'tcp_time_ms' => 'decimal:2',
            'tls_time_ms' => 'decimal:2',
            'status_code' => 'integer',
            'response_headers' => 'array',
            'checked_at' => 'datetime',
        ];
    }

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(Monitor::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(MonitorLocation::class, 'location_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(MonitoringAgent::class, 'agent_id');
    }
}
