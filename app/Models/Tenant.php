<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($tenant) {
            $tenant->uuid = (string) Str::uuid();
        });
    }

    public function users()
    {
        return $this->hasMany(User::class, 'tenant_id');
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function subscription()
    {
        return $this->hasOne(TenantSubscription::class);
    }
}
