<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('visibility')->default('public'); // public, private, password_protected, subscriber_only
            $table->string('password_hash')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('favicon_url')->nullable();
            $table->string('primary_color')->default('#3B82F6');
            $table->string('accent_color')->default('#10B981');
            $table->string('custom_domain')->nullable()->unique();
            $table->boolean('custom_domain_verified')->default(false);
            $table->text('custom_css')->nullable();
            $table->text('header_text')->nullable();
            $table->text('footer_text')->nullable();
            $table->boolean('show_uptime_history')->default(true);
            $table->boolean('show_incident_history')->default(true);
            $table->boolean('show_latency_chart')->default(true);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('status_page_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('status_page_id')->constrained()->cascadeOnDelete();
            $table->foreignId('monitor_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('group_name')->default('Core Services');
            $table->integer('order_index')->default(0);
            $table->string('status')->default('operational'); // operational, degraded, partial_outage, major_outage, maintenance
            $table->timestamps();
        });

        Schema::create('status_page_subscribers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('status_page_id')->constrained()->cascadeOnDelete();
            $table->string('subscription_type')->default('email'); // email, webhook
            $table->string('destination'); // email or webhook url
            $table->string('verification_token')->nullable()->unique();
            $table->boolean('is_verified')->default(true);
            $table->json('subscribed_components')->nullable(); // null = all components
            $table->timestamp('subscribed_at')->useCurrent();
            $table->timestamps();

            $table->unique(['status_page_id', 'destination', 'subscription_type']);
        });

        Schema::create('sla_policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('monitor_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->decimal('target_uptime_percentage', 5, 2)->default(99.90);
            $table->string('measurement_window')->default('monthly'); // monthly, quarterly, annual
            $table->boolean('exclude_maintenance')->default(true);
            $table->decimal('error_budget_alert_threshold', 5, 2)->default(80.00); // alert when 80% error budget consumed
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('sla_compliance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sla_policy_id')->constrained()->cascadeOnDelete();
            $table->foreignId('monitor_id')->constrained()->cascadeOnDelete();
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('target_uptime_percentage', 5, 2);
            $table->decimal('measured_uptime_percentage', 5, 2);
            $table->integer('total_minutes');
            $table->integer('downtime_minutes');
            $table->integer('error_budget_minutes');
            $table->integer('error_budget_consumed_minutes');
            $table->boolean('is_breached')->default(false);
            $table->timestamp('calculated_at')->useCurrent();

            $table->index(['sla_policy_id', 'period_start']);
        });

        Schema::create('maintenance_windows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->boolean('suppress_notifications')->default(true);
            $table->boolean('mark_monitors_maintenance')->default(true);
            $table->string('status')->default('scheduled'); // scheduled, in_progress, completed, cancelled
            $table->timestamps();
        });

        Schema::create('maintenance_monitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maintenance_window_id')->constrained()->cascadeOnDelete();
            $table->foreignId('monitor_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['maintenance_window_id', 'monitor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_monitors');
        Schema::dropIfExists('maintenance_windows');
        Schema::dropIfExists('sla_compliance_records');
        Schema::dropIfExists('sla_policies');
        Schema::dropIfExists('status_page_subscribers');
        Schema::dropIfExists('status_page_components');
        Schema::dropIfExists('status_pages');
    }
};
