<?php

namespace App\Domain\StatusPages\Models;

use App\Domain\Monitoring\Models\Monitor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusPageComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_page_id',
        'monitor_id',
        'name',
        'description',
        'group_name',
        'order_index',
        'status',
    ];

    public function statusPage(): BelongsTo
    {
        return $this->belongsTo(StatusPage::class);
    }

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(Monitor::class);
    }

    /**
     * Get dynamic current status from attached monitor if bound.
     */
    public function getCurrentStatusAttribute(): string
    {
        if ($this->monitor) {
            return match ($this->monitor->status) {
                'healthy' => 'operational',
                'degraded' => 'degraded',
                'suspected_down' => 'partial_outage',
                'down' => 'major_outage',
                'maintenance' => 'maintenance',
                default => 'operational',
            };
        }

        return $this->status ?: 'operational';
    }
}
