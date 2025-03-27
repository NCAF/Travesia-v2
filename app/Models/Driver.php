<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Driver extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'role',
    ];  

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $table = 'driver';

    public function getImageAttribute($value)
    {
        if ($value && !str_starts_with($value, 'http')) {
            return asset('images/' . $value);
        }
        return $value;
    }

    public function setImageAttribute($value)
    {
        // If $value is a file upload, store it
        if (is_object($value) && method_exists($value, 'store')) {
            $this->attributes['image'] = $value->store('images', 'public');
        } 
        // If $value is already a path string, use it directly
        else {
            $this->attributes['image'] = $value;
        }
    }
}
