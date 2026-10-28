<?php

namespace App\Domain\Alerts\Models;

use App\Domain\Incidents\Models\Incident;
use App\Domain\Monitoring\Models\Monitor;
use App\Domain\Tenancy\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationDelivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'notification_channel_id',
        'incident_id',
        'monitor_id',
        'channel_type',
        'recipient',
        'subject',
        'body',
        'status',
        'error_message',
        'provider_response',
        'sent_at',
        'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'provider_response' => 'array',
            'sent_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(NotificationChannel::class, 'notification_channel_id');
    }

    public function incident(): BelongsTo
    {
        return $this->belongsTo(Incident::class);
    }

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(Monitor::class);
    }
}
