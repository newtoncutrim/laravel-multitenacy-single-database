<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'segment_id',
        'name',
        'slug',
        'status',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($tenant) {
            $tenant->uuid = (string) Str::uuid();
            $tenant->slug ??= Str::slug($tenant->name).'-'.Str::lower(Str::random(6));
        });
    }

    public function segment()
    {
        return $this->belongsTo(Segment::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'tenant_id');
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function subscription()
    {
        return $this->hasOne(TenantSubscription::class);
    }

    public function tenantModules()
    {
        return $this->hasMany(TenantModule::class);
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'tenant_modules')
            ->withPivot(['enabled', 'config', 'enabled_at'])
            ->withTimestamps();
    }

    public function enabledModules()
    {
        return $this->modules()
            ->wherePivot('enabled', true)
            ->where('modules.active', true);
    }

    public function settings()
    {
        return $this->hasMany(TenantSetting::class);
    }

    public function branding()
    {
        return $this->hasOne(TenantBranding::class);
    }

    public function hasModule(string $moduleKey): bool
    {
        return $this->enabledModules()
            ->where('modules.key', $moduleKey)
            ->exists();
    }
}
