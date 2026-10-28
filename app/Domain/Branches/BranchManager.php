<?php

namespace App\Domain\Branches;

use App\Domain\Branches\Models\Branch;

class BranchManager
{
    protected static ?Branch $currentBranch = null;

    public static function setBranch(?Branch $branch): void
    {
        static::$currentBranch = $branch;
    }

    public static function getBranch(): ?Branch
    {
        return static::$currentBranch;
    }

    public static function getBranchId(): ?int
    {
        return static::$currentBranch?->id;
    }

    public static function hasBranch(): bool
    {
        return static::$currentBranch !== null;
    }

    public static function clear(): void
    {
        static::$currentBranch = null;
    }
}
