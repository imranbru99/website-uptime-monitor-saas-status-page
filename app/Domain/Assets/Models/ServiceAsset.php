<?php

namespace App\Domain\Assets\Models;

use App\Domain\Branches\Traits\BelongsToBranch;
use App\Domain\Monitoring\Models\Monitor;
use App\Domain\Tenancy\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceAsset extends Model
{
    use BelongsToBranch, BelongsToTenant, HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'monitor_id',
        'name',
        'asset_type',
        'environment',
        'endpoint_url',
        'ip_address',
        'criticality',
        'ownership_team',
        'notes',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(Monitor::class);
    }

    public function dependencies(): BelongsToMany
    {
        return $this->belongsToMany(
            ServiceAsset::class,
            'asset_dependencies',
            'parent_asset_id',
            'child_asset_id'
        )->withPivot('dependency_type', 'description')->withTimestamps();
    }

    public function dependents(): BelongsToMany
    {
        return $this->belongsToMany(
            ServiceAsset::class,
            'asset_dependencies',
            'child_asset_id',
            'parent_asset_id'
        )->withPivot('dependency_type', 'description')->withTimestamps();
    }
}
