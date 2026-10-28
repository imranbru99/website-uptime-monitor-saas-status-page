<?php

namespace App\Domain\Monitoring\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MonitorLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'city',
        'country',
        'region',
        'latitude',
        'longitude',
        'ip_address',
        'status',
        'last_heartbeat_at',
        'capabilities',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'capabilities' => 'array',
            'last_heartbeat_at' => 'datetime',
        ];
    }

    public function checks(): HasMany
    {
        return $this->hasMany(MonitorCheck::class, 'location_id');
    }

    public function agents(): HasMany
    {
        return $this->hasMany(MonitoringAgent::class, 'location_id');
    }
}
