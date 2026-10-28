<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('category'); // monitoring, observability, incidents, alerts, status_pages, reliability, ai, agency, billing, security, cms, integrations
            $table->text('description');
            $table->string('icon')->nullable();
            $table->string('version')->default('1.0.0');
            $table->json('dependencies')->nullable(); // array of module slugs
            $table->json('required_permissions')->nullable();
            $table->string('plan_tier')->default('free'); // free, starter, professional, business, enterprise
            $table->boolean('is_mandatory')->default(false); // identity, tenancy cannot be disabled
            $table->boolean('is_core')->default(false);
            $table->boolean('default_enabled')->default(true);
            $table->boolean('allow_branch_override')->default(true);
            $table->timestamps();
        });

        Schema::create('tenant_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('module_slug');
            $table->boolean('is_enabled')->default(true);
            $table->json('settings')->nullable();
            $table->timestamp('enabled_at')->nullable();
            $table->timestamp('disabled_at')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'module_slug']);
            $table->foreign('module_slug')->references('slug')->on('modules')->cascadeOnDelete();
        });

        Schema::create('branch_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('module_slug');
            $table->boolean('is_enabled')->default(true);
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->unique(['branch_id', 'module_slug']);
            $table->foreign('module_slug')->references('slug')->on('modules')->cascadeOnDelete();
        });

        Schema::create('module_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('module_slug');
            $table->string('action'); // enabled, disabled, configured, synced
            $table->json('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['tenant_id', 'module_slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('module_activity_logs');
        Schema::dropIfExists('branch_modules');
        Schema::dropIfExists('tenant_modules');
        Schema::dropIfExists('modules');
    }
};
