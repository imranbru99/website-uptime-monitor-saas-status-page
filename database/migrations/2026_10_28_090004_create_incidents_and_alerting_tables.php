<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('monitor_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('severity')->default('high'); // critical, high, medium, low, informational
            $table->string('status')->default('detected'); // detected, confirmed, acknowledged, investigating, identified, monitoring_recovery, resolved, closed
            $table->timestamp('detected_at')->useCurrent();
            $table->timestamp('acknowledged_at')->nullable();
            $table->foreignId('acknowledged_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('resolved_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('closed_at')->nullable();
            $table->text('root_cause')->nullable();
            $table->text('resolution_summary')->nullable();
            $table->string('error_classification')->nullable();
            $table->json('impacted_services')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('is_correlated')->default(false);
            $table->unsignedBigInteger('parent_incident_id')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'severity']);
        });

        Schema::create('incident_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incident_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status'); // investigating, identified, monitoring, resolved
            $table->text('message');
            $table->boolean('is_public')->default(true); // whether displayed on status page
            $table->timestamp('posted_at')->useCurrent();
            $table->timestamps();
        });

        Schema::create('incident_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incident_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('comment');
            $table->boolean('is_internal')->default(true);
            $table->timestamps();
        });

        Schema::create('incident_postmortems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incident_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->text('executive_summary')->nullable();
            $table->text('impact_summary')->nullable();
            $table->json('timeline_events')->nullable();
            $table->text('root_cause_analysis')->nullable();
            $table->json('contributing_factors')->nullable();
            $table->json('preventive_actions')->nullable();
            $table->string('status')->default('draft'); // draft, in_review, published
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('notification_channels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('channel_type'); // email, sms, slack, discord, telegram, teams, whatsapp, webhook
            $table->text('destination'); // email address, webhook URL, phone number, channel ID
            $table->json('configuration')->nullable(); // auth tokens, headers, template overrides
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(true);
            $table->timestamp('last_tested_at')->nullable();
            $table->string('last_test_status')->nullable();
            $table->timestamps();
        });

        Schema::create('alert_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('monitor_id')->nullable()->constrained()->nullOnDelete(); // null = all monitors
            $table->string('name');
            $table->string('event_type'); // down, degraded, recovered, ssl_expiring, domain_expiring, high_latency, heartbeat_missed
            $table->integer('failure_threshold')->default(1);
            $table->integer('latency_threshold_ms')->nullable();
            $table->integer('ssl_expiry_days_threshold')->nullable();
            $table->json('quiet_hours')->nullable(); // ['enabled' => true, 'start' => '22:00', 'end' => '07:00', 'timezone' => 'UTC']
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('alert_rule_channel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alert_rule_id')->constrained()->cascadeOnDelete();
            $table->foreignId('notification_channel_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['alert_rule_id', 'notification_channel_id']);
        });

        Schema::create('notification_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('notification_channel_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('incident_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('monitor_id')->nullable()->constrained()->nullOnDelete();
            $table->string('channel_type');
            $table->string('recipient');
            $table->string('subject')->nullable();
            $table->text('body')->nullable();
            $table->string('status')->default('queued'); // queued, sent, delivered, failed
            $table->text('error_message')->nullable();
            $table->json('provider_response')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
        });

        Schema::create('on_call_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('timezone')->default('UTC');
            $table->string('rotation_type')->default('weekly'); // daily, weekly, custom
            $table->dateTime('starts_at');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('on_call_rotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('on_call_schedule_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('priority')->default('primary'); // primary, secondary, escalation
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->timestamps();
        });

        Schema::create('on_call_overrides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('on_call_schedule_id')->constrained()->cascadeOnDelete();
            $table->foreignId('original_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('replacement_user_id')->constrained('users')->cascadeOnDelete();
            $table->text('reason')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('on_call_overrides');
        Schema::dropIfExists('on_call_rotations');
        Schema::dropIfExists('on_call_schedules');
        Schema::dropIfExists('notification_deliveries');
        Schema::dropIfExists('alert_rule_channel');
        Schema::dropIfExists('alert_rules');
        Schema::dropIfExists('notification_channels');
        Schema::dropIfExists('incident_postmortems');
        Schema::dropIfExists('incident_comments');
        Schema::dropIfExists('incident_updates');
        Schema::dropIfExists('incidents');
    }
};
