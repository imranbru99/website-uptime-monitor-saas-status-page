<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('provider'); // slack, teams, discord, telegram, pagerduty, opsgenie, jira, linear, github, prometheus, cloudflare, webhook
            $table->string('name');
            $table->text('credentials')->nullable(); // encrypted JSON
            $table->json('settings')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('health_status')->default('healthy'); // healthy, degraded, failed
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'provider']);
        });

        Schema::create('automation_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('trigger_event'); // monitor.down, monitor.recovered, ssl.expiring, incident.created, heartbeat.missed, sla.breached
            $table->json('conditions')->nullable(); // rules/filters
            $table->json('actions'); // send_notification, create_incident, assign_responder, webhook_call, update_status_page
            $table->integer('executions_count')->default(0);
            $table->timestamp('last_executed_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('automation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('automation_rule_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('trigger_event');
            $table->json('context')->nullable();
            $table->json('executed_actions')->nullable();
            $table->string('status')->default('success'); // success, failed, skipped
            $table->text('error_message')->nullable();
            $table->timestamp('executed_at')->useCurrent();
        });

        Schema::create('service_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('monitor_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('asset_type'); // website, domain, api, server, database, certificate, cloud_resource
            $table->string('environment')->default('production'); // production, staging, development
            $table->string('endpoint_url')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('criticality')->default('high'); // mission_critical, high, medium, low
            $table->string('ownership_team')->nullable();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('asset_dependencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_asset_id')->constrained('service_assets')->cascadeOnDelete();
            $table->foreignId('child_asset_id')->constrained('service_assets')->cascadeOnDelete();
            $table->string('dependency_type')->default('hard'); // hard, soft, fallback
            $table->string('description')->nullable();
            $table->timestamps();

            $table->unique(['parent_asset_id', 'child_asset_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_dependencies');
        Schema::dropIfExists('service_assets');
        Schema::dropIfExists('automation_logs');
        Schema::dropIfExists('automation_rules');
        Schema::dropIfExists('integrations');
    }
};
