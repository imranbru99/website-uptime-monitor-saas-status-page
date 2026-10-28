<?php

namespace App\Domain\Modules\Services;

use App\Domain\Branches\BranchManager;
use App\Domain\Branches\Models\Branch;
use App\Domain\Modules\Models\BranchModule;
use App\Domain\Modules\Models\Module;
use App\Domain\Modules\Models\ModuleActivityLog;
use App\Domain\Modules\Models\TenantModule;
use App\Domain\Tenancy\Models\Tenant;
use App\Domain\Tenancy\TenantManager;
use App\Models\User;
use Illuminate\Support\Collection;

class ModuleManager
{
    /**
     * Determine if a module is enabled for the given (or active) tenant and branch.
     */
    public static function isEnabled(string $moduleSlug, ?Tenant $tenant = null, ?Branch $branch = null): bool
    {
        $tenant = $tenant ?? TenantManager::getTenant();
        $branch = $branch ?? BranchManager::getBranch();

        $module = Module::where('slug', $moduleSlug)->first();
        if (! $module) {
            return true;
        }

        // Mandatory modules can never be disabled
        if ($module->is_mandatory) {
            return true;
        }

        if (! $tenant) {
            return (bool) $module->default_enabled;
        }

        // Check branch level override if branch exists and override is allowed
        if ($branch && $module->allow_branch_override) {
            $branchModule = BranchModule::where('branch_id', $branch->id)
                ->where('module_slug', $moduleSlug)
                ->first();

            if ($branchModule !== null) {
                return (bool) $branchModule->is_enabled;
            }
        }

        // Check tenant level
        $tenantModule = TenantModule::where('tenant_id', $tenant->id)
            ->where('module_slug', $moduleSlug)
            ->first();

        if ($tenantModule !== null) {
            return (bool) $tenantModule->is_enabled;
        }

        return (bool) $module->default_enabled;
    }

    /**
     * Enable a module for a tenant (and optionally a branch).
     */
    public static function enable(string $moduleSlug, Tenant $tenant, ?Branch $branch = null, ?User $user = null): bool
    {
        $module = Module::where('slug', $moduleSlug)->first();
        if (! $module) {
            return false;
        }

        if ($branch && $module->allow_branch_override) {
            BranchModule::updateOrCreate(
                ['branch_id' => $branch->id, 'module_slug' => $moduleSlug],
                ['is_enabled' => true]
            );
        } else {
            TenantModule::updateOrCreate(
                ['tenant_id' => $tenant->id, 'module_slug' => $moduleSlug],
                ['is_enabled' => true, 'enabled_at' => now(), 'disabled_at' => null]
            );
        }

        ModuleActivityLog::create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch?->id,
            'user_id' => $user?->id ?? auth()->id(),
            'module_slug' => $moduleSlug,
            'action' => 'enabled',
            'created_at' => now(),
        ]);

        return true;
    }

    /**
     * Disable a module for a tenant (and optionally a branch).
     */
    public static function disable(string $moduleSlug, Tenant $tenant, ?Branch $branch = null, ?User $user = null): bool
    {
        $module = Module::where('slug', $moduleSlug)->first();
        if (! $module || $module->is_mandatory) {
            return false;
        }

        if ($branch && $module->allow_branch_override) {
            BranchModule::updateOrCreate(
                ['branch_id' => $branch->id, 'module_slug' => $moduleSlug],
                ['is_enabled' => false]
            );
        } else {
            TenantModule::updateOrCreate(
                ['tenant_id' => $tenant->id, 'module_slug' => $moduleSlug],
                ['is_enabled' => false, 'disabled_at' => now()]
            );
        }

        ModuleActivityLog::create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch?->id,
            'user_id' => $user?->id ?? auth()->id(),
            'module_slug' => $moduleSlug,
            'action' => 'disabled',
            'created_at' => now(),
        ]);

        return true;
    }

    /**
     * Get all modules with their current enablement state for a tenant.
     */
    public static function getModulesForTenant(Tenant $tenant, ?Branch $branch = null): Collection
    {
        return Module::all()->map(function (Module $module) use ($tenant, $branch) {
            $module->current_enabled = static::isEnabled($module->slug, $tenant, $branch);

            return $module;
        });
    }
}
