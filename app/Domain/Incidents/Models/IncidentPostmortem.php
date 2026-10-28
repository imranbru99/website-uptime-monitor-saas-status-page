<?php

namespace App\Domain\Incidents\Models;

use App\Domain\Tenancy\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncidentPostmortem extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_id',
        'tenant_id',
        'executive_summary',
        'impact_summary',
        'timeline_events',
        'root_cause_analysis',
        'contributing_factors',
        'preventive_actions',
        'status',
        'author_id',
        'approved_by_id',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'timeline_events' => 'array',
            'contributing_factors' => 'array',
            'preventive_actions' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function incident(): BelongsTo
    {
        return $this->belongsTo(Incident::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }
}
