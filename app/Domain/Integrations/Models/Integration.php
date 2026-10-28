<?php

namespace App\Domain\Integrations\Models;

use App\Domain\Branches\Traits\BelongsToBranch;
use App\Domain\Tenancy\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    use BelongsToBranch, BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'provider',
        'name',
        'credentials',
        'settings',
        'is_active',
        'health_status',
        'last_synced_at',
    ];

    protected function casts(): array
    {
        return [
            'credentials' => 'encrypted:array',
            'settings' => 'array',
            'is_active' => 'boolean',
            'last_synced_at' => 'datetime',
        ];
    }
}
