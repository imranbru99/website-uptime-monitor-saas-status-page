<?php

namespace App\Domain\Automation\Models;

use App\Domain\Tenancy\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AutomationLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'automation_rule_id',
        'tenant_id',
        'trigger_event',
        'context',
        'executed_actions',
        'status',
        'error_message',
        'executed_at',
    ];

    protected function casts(): array
    {
        return [
            'context' => 'array',
            'executed_actions' => 'array',
            'executed_at' => 'datetime',
        ];
    }

    public function rule(): BelongsTo
    {
        return $this->belongsTo(AutomationRule::class, 'automation_rule_id');
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
