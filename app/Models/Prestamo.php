<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prestamo extends Model
{
    use HasFactory;

    protected $fillable = ['equipo_id', 'user_id', 'fecha_prestamo', 'fecha_devolucion'];

    protected $casts = [
        'fecha_prestamo' => 'date',
        'fecha_devolucion' => 'date',
    ];

    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class);
    }

    /** El estudiante a quien se le prestó el equipo. */
    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
