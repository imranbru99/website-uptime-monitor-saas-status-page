<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price_monthly', 10, 2)->default(0.00);
            $table->decimal('price_yearly', 10, 2)->default(0.00);
            $table->string('currency', 4)->default('USD');
            $table->integer('max_monitors')->default(10);
            $table->integer('min_check_interval_seconds')->default(60);
            $table->integer('max_status_pages')->default(1);
            $table->integer('max_users')->default(3);
            $table->integer('max_branches')->default(1);
            $table->integer('max_alert_channels')->default(3);
            $table->integer('retention_days')->default(30);
            $table->boolean('allow_api')->default(false);
            $table->boolean('allow_ai')->default(false);
            $table->boolean('allow_agency')->default(false);
            $table->boolean('allow_white_label')->default(false);
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('subscription_plans')->cascadeOnDelete();
            $table->string('status')->default('trialing'); // trialing, active, past_due, canceled, expired
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('current_period_starts_at')->nullable();
            $table->timestamp('current_period_ends_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('billing_cycle')->default('monthly'); // monthly, yearly
            $table->string('external_id')->nullable(); // stripe / provider subscription ID
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subscription_id')->nullable()->constrained()->nullOnDelete();
            $table->string('invoice_number')->unique();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2)->default(0.00);
            $table->decimal('total', 10, 2);
            $table->string('currency', 4)->default('USD');
            $table->string('status')->default('paid'); // draft, open, paid, void, uncollectible
            $table->date('due_date');
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('discount_type')->default('percentage'); // percentage, fixed
            $table->decimal('discount_value', 8, 2);
            $table->timestamp('valid_until')->nullable();
            $table->integer('max_redemptions')->nullable();
            $table->integer('times_redeemed')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('subscription_plans');
    }
};
