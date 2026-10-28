<?php

namespace App\Domain\Alerts\Models;

use App\Domain\Branches\Traits\BelongsToBranch;
use App\Domain\Tenancy\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NotificationChannel extends Model
{
    use BelongsToBranch, BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'name',
        'channel_type',
        'destination',
        'configuration',
        'is_active',
        'is_verified',
        'last_tested_at',
        'last_test_status',
    ];

    protected function casts(): array
    {
        return [
            'configuration' => 'array',
            'is_active' => 'boolean',
            'is_verified' => 'boolean',
            'last_tested_at' => 'datetime',
        ];
    }

    public function alertRules(): BelongsToMany
    {
        return $this->belongsToMany(AlertRule::class, 'alert_rule_channel');
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(NotificationDelivery::class);
    }
}
