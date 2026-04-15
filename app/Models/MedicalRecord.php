<?php

namespace App\Models;

use App\Tenant\Traits\TenantTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;
    use TenantTrait;

    protected $fillable = [
        'tenant_id',
        'client_id',
        'pet_id',
        'user_id',
        'appointment_id',
        'recorded_at',
        'weight',
        'temperature',
        'diagnosis',
        'prescription',
        'notes',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'weight' => 'decimal:2',
        'temperature' => 'decimal:1',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function entries()
    {
        return $this->hasMany(MedicalRecordEntry::class);
    }
}
