<?php

namespace App\Models;

use App\Tenant\Traits\TenantTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccine extends Model
{
    use HasFactory;
    use TenantTrait;

    protected $fillable = [
        'tenant_id',
        'name',
        'species',
        'manufacturer',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
