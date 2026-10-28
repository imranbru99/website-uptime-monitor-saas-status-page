<?php

namespace App\Domain\Automation\Models;

use App\Domain\Branches\Traits\BelongsToBranch;
use App\Domain\Tenancy\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AutomationRule extends Model
{
    use BelongsToBranch, BelongsToTenant, HasFactory;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'name',
        'trigger_event',
        'conditions',
        'actions',
        'executions_count',
        'last_executed_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'conditions' => 'array',
            'actions' => 'array',
            'executions_count' => 'integer',
            'last_executed_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function logs(): HasMany
    {
        return $this->hasMany(AutomationLog::class);
    }
}
