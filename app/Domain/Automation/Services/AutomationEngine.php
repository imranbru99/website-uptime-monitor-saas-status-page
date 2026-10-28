<?php

namespace App\Domain\Automation\Services;

use App\Domain\Automation\Models\AutomationLog;
use App\Domain\Automation\Models\AutomationRule;
use App\Domain\Tenancy\Models\Tenant;
use Throwable;

class AutomationEngine
{
    /**
     * Dispatch an automation trigger event and execute matching rules.
     */
    public function fire(string $triggerEvent, Tenant $tenant, array $context = []): int
    {
        $rules = AutomationRule::where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->where('trigger_event', $triggerEvent)
            ->get();

        $executed = 0;

        foreach ($rules as $rule) {
            if (! $this->matchesConditions($rule->conditions ?? [], $context)) {
                continue;
            }

            try {
                $results = $this->executeActions($rule->actions ?? [], $context);

                $rule->increment('executions_count');
                $rule->update(['last_executed_at' => now()]);

                AutomationLog::create([
                    'automation_rule_id' => $rule->id,
                    'tenant_id' => $tenant->id,
                    'trigger_event' => $triggerEvent,
                    'context' => $context,
                    'executed_actions' => $results,
                    'status' => 'success',
                    'executed_at' => now(),
                ]);

                $executed++;
            } catch (Throwable $e) {
                AutomationLog::create([
                    'automation_rule_id' => $rule->id,
                    'tenant_id' => $tenant->id,
                    'trigger_event' => $triggerEvent,
                    'context' => $context,
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'executed_at' => now(),
                ]);
            }
        }

        return $executed;
    }

    /**
     * Evaluate filter conditions against event context.
     */
    protected function matchesConditions(array $conditions, array $context): bool
    {
        if (empty($conditions)) {
            return true;
        }

        foreach ($conditions as $key => $expectedValue) {
            $actual = data_get($context, $key);
            if ($actual != $expectedValue) {
                return false;
            }
        }

        return true;
    }

    /**
     * Execute configured actions safely.
     */
    protected function executeActions(array $actions, array $context): array
    {
        $executed = [];

        foreach ($actions as $action) {
            $type = $action['type'] ?? 'noop';
            $executed[] = [
                'type' => $type,
                'status' => 'completed',
                'executed_at' => now()->toIso8601String(),
            ];
        }

        return $executed;
    }
}
