<?php

namespace App\Domain\Billing\Models;

use App\Domain\Tenancy\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price_monthly',
        'price_yearly',
        'currency',
        'max_monitors',
        'min_check_interval_seconds',
        'max_status_pages',
        'max_users',
        'max_branches',
        'max_alert_channels',
        'retention_days',
        'allow_api',
        'allow_ai',
        'allow_agency',
        'allow_white_label',
        'features',
        'is_active',
        'is_featured',
        'order_index',
    ];

    protected function casts(): array
    {
        return [
            'price_monthly' => 'decimal:2',
            'price_yearly' => 'decimal:2',
            'max_monitors' => 'integer',
            'min_check_interval_seconds' => 'integer',
            'max_status_pages' => 'integer',
            'max_users' => 'integer',
            'max_branches' => 'integer',
            'max_alert_channels' => 'integer',
            'retention_days' => 'integer',
            'allow_api' => 'boolean',
            'allow_ai' => 'boolean',
            'allow_agency' => 'boolean',
            'allow_white_label' => 'boolean',
            'features' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class, 'subscription_plan_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }
}
