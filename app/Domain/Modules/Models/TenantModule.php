<?php

namespace App\Domain\Modules\Models;

use App\Domain\Tenancy\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'module_slug',
        'is_enabled',
        'settings',
        'enabled_at',
        'disabled_at',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'settings' => 'array',
            'enabled_at' => 'datetime',
            'disabled_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_slug', 'slug');
    }
}
