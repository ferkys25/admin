<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Modelo de usuarios para el inicio de sesion y control de roles
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Funciones rapidas para saber que rol tiene el usuario
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isProfesor(): bool
    {
        return $this->role === 'profesor';
    }

    public function isEstudiante(): bool
    {
        return $this->role === 'estudiante';
    }
}
