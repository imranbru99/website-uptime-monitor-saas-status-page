<?php

namespace App\Domain\StatusPages\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class StatusPageSubscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_page_id',
        'subscription_type',
        'destination',
        'verification_token',
        'is_verified',
        'subscribed_components',
        'subscribed_at',
    ];

    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
            'subscribed_components' => 'array',
            'subscribed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (StatusPageSubscriber $subscriber) {
            if (empty($subscriber->verification_token)) {
                $subscriber->verification_token = Str::random(32);
            }
        });
    }

    public function statusPage(): BelongsTo
    {
        return $this->belongsTo(StatusPage::class);
    }
}
