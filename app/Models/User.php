<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'role', 'posyandu_id',
    ];

    public function posyandu()
    {
        return $this->belongsTo(Posyandu::class);
    }

    public function pengukuran()
    {
        return $this->hasMany(Pengukuran::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isKader(): bool
    {
        return $this->role === 'kader';
    }
}
