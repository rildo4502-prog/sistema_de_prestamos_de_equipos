<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Prestamo;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

/**
 * Panel inicial del sistema. Es la vista que respalda el layout base
 * (navbar + sidebar) y muestra los indicadores según el rol del usuario.
 */
class PanelController extends Controller
{
    public function __invoke(Request $request): View
    {
        $error = null;

        try {
            $totales = [
                'equipos' => Equipo::count(),
                'disponibles' => Equipo::disponibles()->count(),
                'prestados' => Equipo::where('estado', Equipo::PRESTADO)->count(),
                'prestamos_activos' => Prestamo::whereNull('fecha_devolucion')->count(),
            ];

            // El estudiante sólo ve sus propios préstamos activos.
            $misPrestamos = Prestamo::query()
                ->where('user_id', $request->user()->id)
                ->whereNull('fecha_devolucion')
                ->with('equipo')
                ->latest('fecha_prestamo')
                ->get();
        } catch (\Throwable $e) {
            // Se registra la traza completa en el log (storage/logs) y se
            // muestran datos vacíos con un aviso en la vista.
            report($e);

            $totales = [
                'equipos' => 0,
                'disponibles' => 0,
                'prestados' => 0,
                'prestamos_activos' => 0,
            ];

            $misPrestamos = new Collection;
            $error = 'No se pudo cargar la información del sistema en este momento. Inténtalo nuevamente.';
        }

        return view('panel', [
            'totales' => $totales,
            'misPrestamos' => $misPrestamos,
            'error' => $error,
        ]);
    }
}
