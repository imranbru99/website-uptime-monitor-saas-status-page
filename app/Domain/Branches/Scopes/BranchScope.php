<?php

namespace App\Domain\Branches\Scopes;

use App\Domain\Branches\BranchManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BranchScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (BranchManager::hasBranch()) {
            $builder->where($model->qualifyColumn('branch_id'), BranchManager::getBranchId());
        }
    }
}
