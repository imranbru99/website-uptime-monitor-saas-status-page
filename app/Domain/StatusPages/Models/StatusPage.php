<?php

namespace App\Domain\StatusPages\Models;

use App\Domain\Branches\Traits\BelongsToBranch;
use App\Domain\Tenancy\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use ImranDev\UniversalSlug\Facades\UniversalSlug;

class StatusPage extends Model
{
    use BelongsToBranch, BelongsToTenant, HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'title',
        'slug',
        'description',
        'visibility',
        'password_hash',
        'logo_url',
        'favicon_url',
        'primary_color',
        'accent_color',
        'custom_domain',
        'custom_domain_verified',
        'custom_css',
        'header_text',
        'footer_text',
        'show_uptime_history',
        'show_incident_history',
        'show_latency_chart',
        'meta_title',
        'meta_description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'custom_domain_verified' => 'boolean',
            'show_uptime_history' => 'boolean',
            'show_incident_history' => 'boolean',
            'show_latency_chart' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (StatusPage $page) {
            if (empty($page->slug)) {
                $page->slug = UniversalSlug::generate($page->title);
            }
        });
    }

    public function components(): HasMany
    {
        return $this->hasMany(StatusPageComponent::class)->orderBy('order_index');
    }

    public function subscribers(): HasMany
    {
        return $this->hasMany(StatusPageSubscriber::class);
    }

    /**
     * Compute overall system health status from all attached components.
     */
    public function getComputedStatusAttribute(): string
    {
        $statuses = $this->components->pluck('current_status')->toArray();

        if (empty($statuses)) {
            return 'operational';
        }

        if (in_array('major_outage', $statuses)) {
            return 'major_outage';
        }
        if (in_array('partial_outage', $statuses)) {
            return 'partial_outage';
        }
        if (in_array('degraded', $statuses)) {
            return 'degraded';
        }
        if (in_array('maintenance', $statuses)) {
            return 'maintenance';
        }

        return 'operational';
    }

    public function getPublicUrlAttribute(): string
    {
        if ($this->custom_domain && $this->custom_domain_verified) {
            return "https://{$this->custom_domain}";
        }

        return url("/status/{$this->slug}");
    }
}
