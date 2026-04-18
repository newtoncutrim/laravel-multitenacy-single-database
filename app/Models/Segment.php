<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Segment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function modules()
    {
        return $this->belongsToMany(Module::class)
            ->withPivot(['enabled_by_default', 'default_config'])
            ->withTimestamps();
    }

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }
}
