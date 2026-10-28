<?php

namespace App\Domain\Branches\Traits;

use App\Domain\Branches\BranchManager;
use App\Domain\Branches\Models\Branch;
use App\Domain\Branches\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToBranch
{
    public static function bootBelongsToBranch(): void
    {
        static::addGlobalScope(new BranchScope);

        static::creating(function ($model) {
            if (empty($model->branch_id) && BranchManager::hasBranch()) {
                $model->branch_id = BranchManager::getBranchId();
            }
        });
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
