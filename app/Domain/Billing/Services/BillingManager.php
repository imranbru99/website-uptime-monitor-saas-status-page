<?php

namespace App\Domain\Billing\Services;

use App\Domain\Billing\Models\Invoice;
use App\Domain\Billing\Models\Subscription;
use App\Domain\Billing\Models\SubscriptionPlan;
use App\Domain\Tenancy\Models\Tenant;
use Illuminate\Support\Str;

class BillingManager
{
    /**
     * Subscribe or transition a tenant to a new subscription plan.
     */
    public function subscribe(Tenant $tenant, SubscriptionPlan $plan, string $cycle = 'monthly'): Subscription
    {
        $tenant->update(['subscription_plan_id' => $plan->id, 'subscription_status' => 'active']);

        $price = $cycle === 'yearly' ? $plan->price_yearly : $plan->price_monthly;

        $sub = Subscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'current_period_starts_at' => now(),
            'current_period_ends_at' => $cycle === 'yearly' ? now()->addYear() : now()->addMonth(),
            'billing_cycle' => $cycle,
            'payment_method' => 'card',
        ]);

        if ($price > 0) {
            $this->createInvoice($tenant, $sub, (float) $price);
        }

        return $sub;
    }

    /**
     * Create an invoice record.
     */
    public function createInvoice(Tenant $tenant, ?Subscription $sub, float $amount): Invoice
    {
        $num = 'INV-'.strtoupper(Str::random(4)).'-'.rand(1000, 9999);

        return Invoice::create([
            'tenant_id' => $tenant->id,
            'subscription_id' => $sub?->id,
            'invoice_number' => $num,
            'subtotal' => $amount,
            'tax' => 0.00,
            'total' => $amount,
            'currency' => $tenant->currency ?: 'USD',
            'status' => 'paid',
            'due_date' => now()->toDateString(),
            'paid_at' => now(),
            'payment_reference' => 'ch_'.Str::random(24),
            'notes' => 'Subscription charge for '.($sub?->plan?->name ?? 'Service Plan'),
        ]);
    }
}
