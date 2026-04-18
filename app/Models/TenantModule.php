<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'module_id',
        'enabled',
        'config',
        'enabled_at',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'config' => 'array',
        'enabled_at' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
