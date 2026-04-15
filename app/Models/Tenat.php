<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenat extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($tenat) {
            $tenat->uuid = (string) Str::uuid();
        });
    }

    public function users()
    {
        return $this->hasMany(User::class, 'tenat_id');
    }
}
