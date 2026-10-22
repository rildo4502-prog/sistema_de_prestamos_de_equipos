<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Equipo extends Model
{
    use HasFactory;

    public const DISPONIBLE = 'Disponible';

    public const PRESTADO = 'Prestado';

    // Solo estos campos se pueden asignar en masa (Equipo::create / update).
    protected $fillable = ['codigo', 'nombre', 'categoria', 'estado'];

    /** Historial completo de préstamos del equipo. */
    public function prestamos(): HasMany
    {
        return $this->hasMany(Prestamo::class);
    }

    /** Préstamo vigente (el que todavía no tiene fecha de devolución). */
    public function prestamoActivo(): HasOne
    {
        return $this->hasOne(Prestamo::class)->whereNull('fecha_devolucion');
    }

    public function estaDisponible(): bool
    {
        return $this->estado === self::DISPONIBLE;
    }

    /** Para la vista del estudiante: Equipo::disponibles()->get() */
    public function scopeDisponibles($query)
    {
        return $query->where('estado', self::DISPONIBLE);
    }
}
