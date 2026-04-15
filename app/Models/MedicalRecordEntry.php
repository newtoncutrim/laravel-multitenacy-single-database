<?php

namespace App\Models;

use App\Tenant\Traits\TenantTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecordEntry extends Model
{
    use HasFactory;
    use TenantTrait;

    protected $fillable = [
        'tenant_id',
        'medical_record_id',
        'key',
        'value_type',
        'value_text',
        'value_json',
    ];

    protected $casts = [
        'value_json' => 'array',
    ];
}
