<?php

namespace App\Models;

use App\Domain\Branches\Models\Branch;
use App\Domain\Branches\Models\Team;
use App\Domain\Identity\Models\AuditLog;
use App\Domain\Identity\Models\UserPreference;
use App\Domain\Tenancy\Models\Tenant;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_platform_superadmin' => 'boolean',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->is_platform_superadmin) {
            return true;
        }

        return match ($panel->getId()) {
            'platform' => $this->is_platform_superadmin,
            'app' => $this->tenants()->exists() || in_array($this->role, ['owner', 'admin']),
            'branch' => $this->branches()->exists() || in_array($this->role, ['branch_manager', 'owner', 'admin']),
            'client' => $this->role === 'client' || $this->tenants()->wherePivot('role', 'client')->exists(),
            'operations' => in_array($this->role, ['engineer', 'analyst', 'support', 'owner', 'admin']),
            default => true,
        };
    }

    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class, 'tenant_user')
            ->withPivot('role', 'is_owner')
            ->withTimestamps();
    }

    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class, 'branch_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function preferences(): HasOne
    {
        return $this->hasOne(UserPreference::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function currentTenant(): ?Tenant
    {
        if ($this->current_tenant_id) {
            return Tenant::find($this->current_tenant_id);
        }

        return $this->tenants()->first();
    }

    public function currentBranch(): ?Branch
    {
        if ($this->current_branch_id) {
            return Branch::find($this->current_branch_id);
        }

        return $this->branches()->first();
    }
}
