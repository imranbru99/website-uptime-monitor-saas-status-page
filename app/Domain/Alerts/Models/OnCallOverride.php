<?php

namespace App\Domain\Alerts\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OnCallOverride extends Model
{
    use HasFactory;

    protected $fillable = [
        'on_call_schedule_id',
        'original_user_id',
        'replacement_user_id',
        'reason',
        'start_time',
        'end_time',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
        ];
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(OnCallSchedule::class, 'on_call_schedule_id');
    }

    public function originalUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'original_user_id');
    }

    public function replacementUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'replacement_user_id');
    }
}
