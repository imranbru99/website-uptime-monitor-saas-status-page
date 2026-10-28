<?php

namespace App\Domain\Analytics\Models;

use App\Domain\Branches\Traits\BelongsToBranch;
use App\Domain\Monitoring\Models\Monitor;
use App\Domain\Tenancy\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MaintenanceWindow extends Model
{
    use BelongsToBranch, BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'title',
        'description',
        'starts_at',
        'ends_at',
        'suppress_notifications',
        'mark_monitors_maintenance',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'suppress_notifications' => 'boolean',
            'mark_monitors_maintenance' => 'boolean',
        ];
    }

    public function monitors(): BelongsToMany
    {
        return $this->belongsToMany(Monitor::class, 'maintenance_monitors');
    }

    public function isCurrentlyActive(): bool
    {
        $now = now();

        return $this->status === 'in_progress' || ($this->status === 'scheduled' && $now->between($this->starts_at, $this->ends_at));
    }
}
