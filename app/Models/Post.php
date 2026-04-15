<?php

namespace App\Models;

use App\Scope\Tenat\TenatScope;
use App\Tenat\Traits\TenatTrait;
use App\TenatObserver\TenatObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    use TenatTrait;

    protected $fillable = ['user_id', 'title', 'content'];

    public function tenat()
    {
        return $this->belongsTo(Tenat::class, 'tenat_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
