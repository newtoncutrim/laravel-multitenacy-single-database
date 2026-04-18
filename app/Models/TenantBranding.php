<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantBranding extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'logo_path',
        'primary_color',
        'secondary_color',
        'accent_color',
        'custom_domain',
        'extra',
    ];

    protected $casts = [
        'extra' => 'array',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
