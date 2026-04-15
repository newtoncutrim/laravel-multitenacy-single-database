<?php

namespace App\Models;

use App\Tenant\Traits\TenantTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetVaccine extends Model
{
    use HasFactory;
    use TenantTrait;

    protected $fillable = [
        'tenant_id',
        'pet_id',
        'vaccine_id',
        'user_id',
        'administered_at',
        'due_at',
        'batch_number',
        'notes',
    ];

    protected $casts = [
        'administered_at' => 'date',
        'due_at' => 'date',
    ];
}
