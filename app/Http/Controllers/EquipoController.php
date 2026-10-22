<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\Request;

class EquipoController extends Controller
{
    // Mostrar todos los equipos
    public function index()
    {
        $equipos = Equipo::orderBy('id', 'desc')->get();

        return view('equipos.index', compact('equipos'));
    }

    // Mostrar formulario para crear
    public function create()
    {
        return view('equipos.create');
    }

    // Guardar nuevo equipo
    public function store(Request $request)
    {
        $datos = $request->validate([
            'codigo' => 'required|string|max:50|unique:equipos,codigo',
            'nombre' => 'required|string|max:100',
            'categoria' => 'required|string|max:100',
        ]);

        $datos['estado'] = Equipo::DISPONIBLE;

        Equipo::create($datos);

        return redirect()
            ->route('equipos.index')
            ->with('success', 'Equipo registrado correctamente.');
    }

    // Mostrar formulario para editar
    public function edit(Equipo $equipo)
    {
        return view('equipos.edit', compact('equipo'));
    }

    // Actualizar equipo
    public function update(Request $request, Equipo $equipo)
    {
        $datos = $request->validate([
            'codigo' => 'required|string|max:50|unique:equipos,codigo,' . $equipo->id,
            'nombre' => 'required|string|max:100',
            'categoria' => 'required|string|max:100',
        ]);

        $equipo->update($datos);

        return redirect()
            ->route('equipos.index')
            ->with('success', 'Equipo actualizado correctamente.');
    }

    // Eliminar equipo
    public function destroy(Equipo $equipo)
    {
        if ($equipo->estado === Equipo::PRESTADO) {
            return redirect()
                ->route('equipos.index')
                ->with('error', 'No se puede eliminar un equipo que está prestado.');
        }

        $equipo->delete();

        return redirect()
            ->route('equipos.index')
            ->with('success', 'Equipo eliminado correctamente.');
    }
}