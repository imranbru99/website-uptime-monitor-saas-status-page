<?php

namespace App\Domain\Assets\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetDependency extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_asset_id',
        'child_asset_id',
        'dependency_type',
        'description',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ServiceAsset::class, 'parent_asset_id');
    }

    public function child(): BelongsTo
    {
        return $this->belongsTo(ServiceAsset::class, 'child_asset_id');
    }
}
