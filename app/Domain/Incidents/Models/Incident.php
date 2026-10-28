<?php

namespace App\Domain\Incidents\Models;

use App\Domain\Branches\Traits\BelongsToBranch;
use App\Domain\Monitoring\Models\Monitor;
use App\Domain\Tenancy\Traits\BelongsToTenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Incident extends Model
{
    use BelongsToBranch, BelongsToTenant, HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'monitor_id',
        'title',
        'description',
        'severity',
        'status',
        'detected_at',
        'acknowledged_at',
        'acknowledged_by_id',
        'resolved_at',
        'resolved_by_id',
        'closed_at',
        'root_cause',
        'resolution_summary',
        'error_classification',
        'impacted_services',
        'tags',
        'is_correlated',
        'parent_incident_id',
    ];

    protected function casts(): array
    {
        return [
            'detected_at' => 'datetime',
            'acknowledged_at' => 'datetime',
            'resolved_at' => 'datetime',
            'closed_at' => 'datetime',
            'impacted_services' => 'array',
            'tags' => 'array',
            'is_correlated' => 'boolean',
        ];
    }

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(Monitor::class);
    }

    public function acknowledgedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acknowledged_by_id');
    }

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by_id');
    }

    public function updates(): HasMany
    {
        return $this->hasMany(IncidentUpdate::class)->latest('posted_at');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(IncidentComment::class)->latest('created_at');
    }

    public function postmortem(): HasOne
    {
        return $this->hasOne(IncidentPostmortem::class);
    }

    public function parentIncident(): BelongsTo
    {
        return $this->belongsTo(Incident::class, 'parent_incident_id');
    }

    public function childIncidents(): HasMany
    {
        return $this->hasMany(Incident::class, 'parent_incident_id');
    }

    public function acknowledge(User $user): void
    {
        $this->update([
            'status' => 'acknowledged',
            'acknowledged_at' => now(),
            'acknowledged_by_id' => $user->id,
        ]);

        $this->updates()->create([
            'user_id' => $user->id,
            'status' => 'investigating',
            'message' => "Incident acknowledged by {$user->name}. Investigation underway.",
            'is_public' => true,
            'posted_at' => now(),
        ]);
    }

    public function resolve(User $user, ?string $summary = null): void
    {
        $this->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolved_by_id' => $user->id,
            'resolution_summary' => $summary ?? $this->resolution_summary ?? 'Service recovery verified.',
        ]);

        $this->updates()->create([
            'user_id' => $user->id,
            'status' => 'resolved',
            'message' => $summary ?? 'The incident has been resolved and service is fully restored.',
            'is_public' => true,
            'posted_at' => now(),
        ]);
    }
}
