<?php

namespace App\Domain\Alerts\Models;

use App\Domain\Branches\Traits\BelongsToBranch;
use App\Domain\Monitoring\Models\Monitor;
use App\Domain\Tenancy\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AlertRule extends Model
{
    use BelongsToBranch, BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'monitor_id',
        'name',
        'event_type',
        'failure_threshold',
        'latency_threshold_ms',
        'ssl_expiry_days_threshold',
        'quiet_hours',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'failure_threshold' => 'integer',
            'latency_threshold_ms' => 'integer',
            'ssl_expiry_days_threshold' => 'integer',
            'quiet_hours' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(Monitor::class);
    }

    public function channels(): BelongsToMany
    {
        return $this->belongsToMany(NotificationChannel::class, 'alert_rule_channel');
    }

    public function isWithinQuietHours(): bool
    {
        $quiet = $this->quiet_hours;
        if (empty($quiet) || empty($quiet['enabled'])) {
            return false;
        }

        $now = now($quiet['timezone'] ?? 'UTC')->format('H:i');
        $start = $quiet['start'] ?? '22:00';
        $end = $quiet['end'] ?? '07:00';

        if ($start > $end) {
            return $now >= $start || $now <= $end;
        }

        return $now >= $start && $now <= $end;
    }
}
