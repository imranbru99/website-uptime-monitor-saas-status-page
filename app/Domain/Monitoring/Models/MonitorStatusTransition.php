<?php

namespace App\Domain\Monitoring\Models;

use App\Domain\Tenancy\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitorStatusTransition extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'monitor_id',
        'tenant_id',
        'from_status',
        'to_status',
        'reason',
        'trigger_check_id',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
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

    public function triggerCheck(): BelongsTo
    {
        return $this->belongsTo(MonitorCheck::class, 'trigger_check_id');
    }
}
