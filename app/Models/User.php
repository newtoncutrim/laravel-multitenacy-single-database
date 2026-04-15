<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLE_SUPER_ADMIN = 'super-admin';

    public const ROLE_PLATFORM_ADMIN = 'platform-admin';

    public const ROLE_SUPPORT = 'support';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'branch_id',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasPermissionTo(string $permission): bool
    {
        return $this->roles()
            ->whereHas('permissions', fn ($query) => $query->where('slug', $permission))
            ->exists();
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()
            ->where('slug', $role)
            ->exists();
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()
            ->whereIn('slug', $roles)
            ->exists();
    }

    public function isPlatformUser(): bool
    {
        return $this->tenant_id === null;
    }

    public function isTenantUser(): bool
    {
        return $this->tenant_id !== null;
    }

    public function isPlatformAdmin(): bool
    {
        return $this->isPlatformUser()
            && $this->hasAnyRole([
                self::ROLE_SUPER_ADMIN,
                self::ROLE_PLATFORM_ADMIN,
            ]);
    }

    public function isSupportUser(): bool
    {
        return $this->isPlatformUser()
            && $this->hasRole(self::ROLE_SUPPORT);
    }

    public function homeRoute(): string
    {
        if ($this->isPlatformAdmin()) {
            return 'platform.dashboard';
        }

        if ($this->isSupportUser()) {
            return 'support.dashboard';
        }

        return 'clinic.dashboard';
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }
}
