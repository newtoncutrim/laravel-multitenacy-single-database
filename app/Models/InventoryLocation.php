<?php

namespace App\Models;

use App\Tenant\Traits\TenantTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLocation extends Model
{
    use HasFactory;
    use TenantTrait;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'name',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
