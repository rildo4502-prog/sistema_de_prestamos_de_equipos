<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Prestamo;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

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

            $misPrestamos = Prestamo::query()
                ->where('user_id', $request->user()->id)
                ->whereNull('fecha_devolucion')
                ->with('equipo')
                ->latest('fecha_prestamo')
                ->get();
        } catch (\Throwable $e) {
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
