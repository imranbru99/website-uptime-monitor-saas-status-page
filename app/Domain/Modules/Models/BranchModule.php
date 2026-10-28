<?php

namespace App\Domain\Modules\Models;

use App\Domain\Branches\Models\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BranchModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'module_slug',
        'is_enabled',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'settings' => 'array',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_slug', 'slug');
    }
}
