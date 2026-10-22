<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Prestamo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrestamoController extends Controller
{
    // Mostrar los préstamos y datos necesarios para prestar
    public function index()
    {
        $prestamos = Prestamo::with(['equipo', 'estudiante'])
            ->orderBy('id', 'desc')
            ->get();

        $equipos = Equipo::where('estado', Equipo::DISPONIBLE)
            ->orderBy('nombre')
            ->get();

        $estudiantes = User::where('rol', User::ROL_ESTUDIANTE)
            ->orderBy('name')
            ->get();

        return view(
            'prestamos.index',
            compact('prestamos', 'equipos', 'estudiantes')
        );
    }

    // Registrar un préstamo
    public function prestar(Request $request)
    {
        $datos = $request->validate([
            'equipo_id' => 'required|exists:equipos,id',
            'user_id' => 'required|exists:users,id',
        ]);

        try {
            DB::transaction(function () use ($datos) {

                $equipo = Equipo::lockForUpdate()
                    ->findOrFail($datos['equipo_id']);

                $estudiante = User::findOrFail($datos['user_id']);

                // Solo se puede prestar a estudiantes
                if (!$estudiante->esEstudiante()) {
                    throw new \Exception(
                        'El usuario seleccionado no es un estudiante.'
                    );
                }

                // Evitar prestar un equipo que ya está prestado
                if (!$equipo->estaDisponible()) {
                    throw new \Exception(
                        'El equipo seleccionado ya se encuentra prestado.'
                    );
                }

                Prestamo::create([
                    'equipo_id' => $equipo->id,
                    'user_id' => $estudiante->id,
                    'fecha_prestamo' => now()->toDateString(),
                    'fecha_devolucion' => null,
                ]);

                $equipo->update([
                    'estado' => Equipo::PRESTADO,
                ]);
            });

            return redirect()
                ->route('prestamos.index')
                ->with('success', 'Préstamo registrado correctamente.');

        } catch (\Throwable $e) {
            return redirect()
                ->route('prestamos.index')
                ->with('error', $e->getMessage());
        }
    }

    // Registrar la devolución
    public function devolver(Prestamo $prestamo)
    {
        try {
            DB::transaction(function () use ($prestamo) {

                $prestamo = Prestamo::lockForUpdate()
                    ->findOrFail($prestamo->id);

                if ($prestamo->fecha_devolucion !== null) {
                    throw new \Exception(
                        'Este préstamo ya fue devuelto.'
                    );
                }

                $prestamo->update([
                    'fecha_devolucion' => now()->toDateString(),
                ]);

                $prestamo->equipo->update([
                    'estado' => Equipo::DISPONIBLE,
                ]);
            });

            return redirect()
                ->route('prestamos.index')
                ->with('success', 'Equipo devuelto correctamente.');

        } catch (\Throwable $e) {
            return redirect()
                ->route('prestamos.index')
                ->with('error', $e->getMessage());
        }
    }
}