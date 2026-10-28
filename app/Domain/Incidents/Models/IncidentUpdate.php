<?php

namespace App\Domain\Incidents\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncidentUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_id',
        'user_id',
        'status',
        'message',
        'is_public',
        'posted_at',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'posted_at' => 'datetime',
        ];
    }

    public function incident(): BelongsTo
    {
        return $this->belongsTo(Incident::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
