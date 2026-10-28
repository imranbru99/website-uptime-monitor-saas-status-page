<?php

namespace App\Domain\Monitoring\Models;

use App\Domain\Tenancy\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Heartbeat extends Model
{
    use HasFactory;

    protected $fillable = [
        'monitor_id',
        'tenant_id',
        'secret_token',
        'interval_seconds',
        'grace_seconds',
        'last_ping_at',
        'next_expected_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'interval_seconds' => 'integer',
            'grace_seconds' => 'integer',
            'last_ping_at' => 'datetime',
            'next_expected_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Heartbeat $heartbeat) {
            if (empty($heartbeat->secret_token)) {
                $heartbeat->secret_token = Str::random(32);
            }
        });
    }

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(Monitor::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function getPingUrlAttribute(): string
    {
        return url("/api/v1/heartbeat/{$this->secret_token}");
    }
}
