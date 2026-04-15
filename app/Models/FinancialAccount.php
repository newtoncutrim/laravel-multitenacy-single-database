<?php

namespace App\Models;

use App\Tenant\Traits\TenantTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialAccount extends Model
{
    use HasFactory;
    use TenantTrait;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'name',
        'type',
        'opening_balance',
        'active',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'active' => 'boolean',
    ];
}
