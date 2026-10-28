<?php

namespace App\Domain\Billing\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'valid_until',
        'max_redemptions',
        'times_redeemed',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'discount_value' => 'decimal:2',
            'valid_until' => 'datetime',
            'max_redemptions' => 'integer',
            'times_redeemed' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function isValid(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->valid_until && now()->isAfter($this->valid_until)) {
            return false;
        }

        if ($this->max_redemptions !== null && $this->times_redeemed >= $this->max_redemptions) {
            return false;
        }

        return true;
    }
}
