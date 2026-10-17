<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public const ROL_ENCARGADO = 'encargado';
    public const ROL_ESTUDIANTE = 'estudiante';

    /**
     * Ojo: 'rol' está en $fillable solo porque los seeders lo necesitan.
     * El formulario de registro NO debe aceptar 'rol' desde el request,
     * si no cualquiera podría registrarse como encargado.
     */
    protected $fillable = [
        'name',
        'email',
        'rol',
        'password',
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

    public function prestamos(): HasMany
    {
        return $this->hasMany(Prestamo::class);
    }

    public function esEncargado(): bool
    {
        return $this->rol === self::ROL_ENCARGADO;
    }

    public function esEstudiante(): bool
    {
        return $this->rol === self::ROL_ESTUDIANTE;
    }
}
