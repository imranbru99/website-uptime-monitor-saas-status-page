<?php

namespace App\Domain\Tenancy;

use App\Domain\Tenancy\Models\Tenant;

class TenantManager
{
    protected static ?Tenant $currentTenant = null;

    public static function setTenant(?Tenant $tenant): void
    {
        static::$currentTenant = $tenant;
    }

    public static function getTenant(): ?Tenant
    {
        return static::$currentTenant;
    }

    public static function getTenantId(): ?int
    {
        return static::$currentTenant?->id;
    }

    public static function hasTenant(): bool
    {
        return static::$currentTenant !== null;
    }

    public static function clear(): void
    {
        static::$currentTenant = null;
    }
}
