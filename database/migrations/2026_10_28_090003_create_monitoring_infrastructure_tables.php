<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitor_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique(); // sin, tyo, lhr, fra, nyc, bom, syd, gru
            $table->string('city');
            $table->string('country', 4);
            $table->string('region'); // asia_pacific, europe, north_america, south_america, etc.
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('status')->default('active'); // active, demo, offline
            $table->timestamp('last_heartbeat_at')->nullable();
            $table->json('capabilities')->nullable(); // ['http', 'tcp', 'dns', 'ssl', 'ping']
            $table->timestamps();
        });

        Schema::create('monitoring_agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('location_id')->nullable()->constrained('monitor_locations')->nullOnDelete();
            $table->string('name');
            $table->string('token_hash')->unique();
            $table->string('type')->default('public_probe'); // public_probe, private_agent, server_agent
            $table->string('version')->default('1.0.0');
            $table->string('os_info')->nullable();
            $table->string('status')->default('online'); // online, degraded, offline
            $table->timestamp('last_seen_at')->nullable();
            $table->json('metrics')->nullable(); // cpu, memory, disk, load
            $table->timestamps();
        });

        Schema::create('monitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('private_agent_id')->nullable()->constrained('monitoring_agents')->nullOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('monitor_type'); // http, https, api, tcp, dns, ssl, domain, ping, port, keyword, synthetic, heartbeat, server, database
            $table->string('target'); // URL, hostname, IP, connection string
            $table->integer('check_interval_seconds')->default(60);
            $table->integer('timeout_seconds')->default(15);
            $table->integer('retry_count')->default(2);
            $table->integer('confirmation_threshold')->default(2); // consecutive failures before marking DOWN
            $table->string('status')->default('pending'); // pending, healthy, degraded, suspected_down, down, recovering, paused, maintenance, unknown
            $table->integer('consecutive_failures')->default(0);
            $table->integer('consecutive_successes')->default(0);
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamp('next_check_due_at')->nullable()->index();
            $table->timestamp('last_status_change_at')->nullable();
            $table->decimal('uptime_percentage_24h', 5, 2)->default(100.00);
            $table->decimal('uptime_percentage_7d', 5, 2)->default(100.00);
            $table->decimal('uptime_percentage_30d', 5, 2)->default(100.00);
            $table->decimal('avg_latency_ms', 8, 2)->default(0.00);
            $table->json('tags')->nullable();
            $table->json('selected_locations')->nullable(); // array of location codes
            $table->json('config')->nullable(); // headers, method, body, expected_status_code, expected_content, forbidden_content, ssl_expiration_days, dns_type, etc.
            $table->boolean('is_active')->default(true);
            $table->boolean('is_paused')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status']);
            $table->index(['is_active', 'is_paused', 'next_check_due_at']);
        });

        Schema::create('monitor_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('location_id')->nullable()->constrained('monitor_locations')->nullOnDelete();
            $table->foreignId('agent_id')->nullable()->constrained('monitoring_agents')->nullOnDelete();
            $table->string('status'); // success, degraded, failed
            $table->integer('status_code')->nullable();
            $table->decimal('latency_ms', 8, 2)->default(0.00);
            $table->decimal('dns_time_ms', 8, 2)->nullable();
            $table->decimal('tcp_time_ms', 8, 2)->nullable();
            $table->decimal('tls_time_ms', 8, 2)->nullable();
            $table->text('error_message')->nullable();
            $table->string('error_type')->nullable(); // connection_timeout, dns_resolution_failed, status_code_mismatch, ssl_expired, ssrf_blocked, keyword_missing, etc.
            $table->json('response_headers')->nullable();
            $table->string('response_snippet', 500)->nullable();
            $table->timestamp('checked_at')->useCurrent();

            $table->index(['monitor_id', 'checked_at']);
            $table->index(['tenant_id', 'checked_at']);
        });

        Schema::create('monitor_status_transitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('from_status');
            $table->string('to_status');
            $table->text('reason')->nullable();
            $table->foreignId('trigger_check_id')->nullable()->constrained('monitor_checks')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['monitor_id', 'created_at']);
        });

        Schema::create('heartbeats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitor_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('secret_token')->unique();
            $table->integer('interval_seconds')->default(300);
            $table->integer('grace_seconds')->default(60);
            $table->timestamp('last_ping_at')->nullable();
            $table->timestamp('next_expected_at')->nullable();
            $table->string('status')->default('pending'); // healthy, late, missing
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('heartbeats');
        Schema::dropIfExists('monitor_status_transitions');
        Schema::dropIfExists('monitor_checks');
        Schema::dropIfExists('monitors');
        Schema::dropIfExists('monitoring_agents');
        Schema::dropIfExists('monitor_locations');
    }
};
