<?php

namespace App\Domain\Alerts\Models;

use App\Domain\Branches\Traits\BelongsToBranch;
use App\Domain\Tenancy\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OnCallSchedule extends Model
{
    use BelongsToBranch, BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'name',
        'timezone',
        'rotation_type',
        'starts_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function rotations(): HasMany
    {
        return $this->hasMany(OnCallRotation::class);
    }

    public function overrides(): HasMany
    {
        return $this->hasMany(OnCallOverride::class);
    }
}
