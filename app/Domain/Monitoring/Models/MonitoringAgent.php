<?php

namespace App\Domain\Monitoring\Models;

use App\Domain\Branches\Models\Branch;
use App\Domain\Tenancy\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MonitoringAgent extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'location_id',
        'name',
        'token_hash',
        'type',
        'version',
        'os_info',
        'status',
        'last_seen_at',
        'metrics',
    ];

    protected function casts(): array
    {
        return [
            'metrics' => 'array',
            'last_seen_at' => 'datetime',
        ];
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

    public function checks(): HasMany
    {
        return $this->hasMany(MonitorCheck::class, 'agent_id');
    }
}
