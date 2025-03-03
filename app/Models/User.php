<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'numberphone',
        'role', 
        'gender', 
        'date_of_birth', 
        'profile_picture'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'user_id');
    }

    public  function notes() : HasMany
    {
        return $this->hasMany(Note::class, 'user_id');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'user_id');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class, 'user_id');
    }
}

