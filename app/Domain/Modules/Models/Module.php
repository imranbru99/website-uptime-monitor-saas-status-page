<?php

namespace App\Domain\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'category',
        'description',
        'icon',
        'version',
        'dependencies',
        'required_permissions',
        'plan_tier',
        'is_mandatory',
        'is_core',
        'default_enabled',
        'allow_branch_override',
    ];

    protected function casts(): array
    {
        return [
            'dependencies' => 'array',
            'required_permissions' => 'array',
            'is_mandatory' => 'boolean',
            'is_core' => 'boolean',
            'default_enabled' => 'boolean',
            'allow_branch_override' => 'boolean',
        ];
    }

    public function tenantModules(): HasMany
    {
        return $this->hasMany(TenantModule::class, 'module_slug', 'slug');
    }

    public function branchModules(): HasMany
    {
        return $this->hasMany(BranchModule::class, 'module_slug', 'slug');
    }
}
