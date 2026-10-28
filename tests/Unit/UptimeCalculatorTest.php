<?php

namespace Tests\Unit;

use App\Domain\Analytics\Services\UptimeCalculator;
use PHPUnit\Framework\TestCase;

class UptimeCalculatorTest extends TestCase
{
    public function test_it_calculates_error_budget_correctly(): void
    {
        $calculator = new UptimeCalculator;

        // 30 days = 43200 minutes. 99.9% target SLA allows 0.1% downtime = 43.2 minutes (~43 min)
        $budget = $calculator->calculateErrorBudget(99.9, 43200, 20);

        $this->assertEquals(99.9, $budget['target_sla']);
        $this->assertEquals(43, $budget['allowed_downtime_minutes']);
        $this->assertEquals(20, $budget['actual_downtime_minutes']);
        $this->assertEquals(23, $budget['budget_remaining_minutes']);
        $this->assertFalse($budget['is_breached']);
    }

    public function test_it_detects_sla_error_budget_breach(): void
    {
        $calculator = new UptimeCalculator;

        // Downtime 50 minutes exceeds 43 minutes allowed
        $budget = $calculator->calculateErrorBudget(99.9, 43200, 50);

        $this->assertTrue($budget['is_breached']);
        $this->assertEquals(100.0, $budget['budget_consumed_percentage']);
    }
}
