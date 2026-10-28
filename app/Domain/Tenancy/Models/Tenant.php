<?php

namespace App\Domain\Tenancy\Models;

use App\Domain\Alerts\Models\AlertRule;
use App\Domain\Alerts\Models\NotificationChannel;
use App\Domain\Analytics\Models\SlaPolicy;
use App\Domain\Billing\Models\Invoice;
use App\Domain\Billing\Models\Subscription;
use App\Domain\Billing\Models\SubscriptionPlan;
use App\Domain\Branches\Models\Branch;
use App\Domain\Incidents\Models\Incident;
use App\Domain\Modules\Models\Module;
use App\Domain\Modules\Models\TenantModule;
use App\Domain\Monitoring\Models\Monitor;
use App\Domain\StatusPages\Models\StatusPage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use ImranDev\UniversalSlug\Facades\UniversalSlug;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'business_mode',
        'email',
        'phone',
        'country',
        'address',
        'timezone',
        'language',
        'currency',
        'logo_url',
        'favicon_url',
        'subscription_plan_id',
        'subscription_status',
        'retention_days',
        'settings',
        'brand_settings',
        'security_settings',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'brand_settings' => 'array',
            'security_settings' => 'array',
            'is_active' => 'boolean',
            'retention_days' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Tenant $tenant) {
            if (empty($tenant->slug)) {
                $tenant->slug = UniversalSlug::generate($tenant->name);
            }
        });
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tenant_user')
            ->withPivot('role', 'is_owner')
            ->withTimestamps();
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    public function monitors(): HasMany
    {
        return $this->hasMany(Monitor::class);
    }

    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class);
    }

    public function statusPages(): HasMany
    {
        return $this->hasMany(StatusPage::class);
    }

    public function notificationChannels(): HasMany
    {
        return $this->hasMany(NotificationChannel::class);
    }

    public function alertRules(): HasMany
    {
        return $this->hasMany(AlertRule::class);
    }

    public function slaPolicies(): HasMany
    {
        return $this->hasMany(SlaPolicy::class);
    }

    public function subscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function currentSubscription(): ?Subscription
    {
        return $this->subscriptions()
            ->whereIn('status', ['active', 'trialing'])
            ->latest()
            ->first();
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function tenantModules(): HasMany
    {
        return $this->hasMany(TenantModule::class);
    }

    public function hasModule(string $moduleSlug): bool
    {
        $module = $this->tenantModules()->where('module_slug', $moduleSlug)->first();
        if ($module) {
            return (bool) $module->is_enabled;
        }

        // Check if mandatory or default enabled
        $base = Module::where('slug', $moduleSlug)->first();

        return $base ? (bool) $base->default_enabled : true;
    }

    public function canAddMonitor(): bool
    {
        $plan = $this->subscriptionPlan;
        if (! $plan) {
            return $this->monitors()->count() < 10;
        }

        return $this->monitors()->count() < $plan->max_monitors;
    }

    public function canAddStatusPage(): bool
    {
        $plan = $this->subscriptionPlan;
        if (! $plan) {
            return $this->statusPages()->count() < 1;
        }

        return $this->statusPages()->count() < $plan->max_status_pages;
    }

    public function canAddBranch(): bool
    {
        $plan = $this->subscriptionPlan;
        if (! $plan) {
            return $this->branches()->count() < 1;
        }

        return $this->branches()->count() < $plan->max_branches;
    }
}
