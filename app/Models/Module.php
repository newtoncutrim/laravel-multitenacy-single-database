<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'description',
        'scope',
        'category',
        'is_core',
        'active',
        'navigation_label',
        'navigation_path',
        'api_prefix',
        'icon',
        'config_schema',
    ];

    protected $casts = [
        'is_core' => 'boolean',
        'active' => 'boolean',
        'config_schema' => 'array',
    ];

    public function segments()
    {
        return $this->belongsToMany(Segment::class)
            ->withPivot(['enabled_by_default', 'default_config'])
            ->withTimestamps();
    }

    public function tenantModules()
    {
        return $this->hasMany(TenantModule::class);
    }

    public function tenants()
    {
        return $this->belongsToMany(Tenant::class, 'tenant_modules')
            ->withPivot(['enabled', 'config', 'enabled_at'])
            ->withTimestamps();
    }
}
