<?php

namespace App\Models;

use App\Tenant\Traits\TenantTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    use TenantTrait;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'client_id',
        'pet_id',
        'medical_record_id',
        'type',
        'title',
        'file_path',
        'mime_type',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];
}
