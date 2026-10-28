<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->after('password');
            $table->boolean('is_platform_superadmin')->default(false)->after('role');
            $table->string('phone')->nullable()->after('is_platform_superadmin');
            $table->string('avatar_url')->nullable()->after('phone');
            $table->unsignedBigInteger('current_tenant_id')->nullable()->index()->after('avatar_url');
            $table->unsignedBigInteger('current_branch_id')->nullable()->index()->after('current_tenant_id');
            $table->string('timezone')->default('UTC')->after('current_branch_id');
            $table->string('locale')->default('en')->after('timezone');
            $table->boolean('is_active')->default(true)->after('locale');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'is_platform_superadmin',
                'phone',
                'avatar_url',
                'current_tenant_id',
                'current_branch_id',
                'timezone',
                'locale',
                'is_active',
                'last_login_at',
            ]);
        });
    }
};
