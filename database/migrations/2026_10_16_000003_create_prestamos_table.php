<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id();

            // Si se borra el equipo se borra su historial; el estudiante
            // no se puede borrar mientras tenga préstamos registrados.
            $table->foreignId('equipo_id')->constrained('equipos')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();

            $table->date('fecha_prestamo');
            // null = el equipo todavía no ha sido devuelto
            $table->date('fecha_devolucion')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestamos');
    }
};
