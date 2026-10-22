@extends('layouts.app')

@section('title', 'Gestión de Equipos')

@section('content')

<h2 class="mb-4">Gestión de Equipos</h2>

<div class="mb-4">
    <a href="{{ route('equipos.create') }}" class="btn btn-primary">
        Nuevo equipo
    </a>
</div>

<div class="card">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($equipos as $equipo)
                        <tr>
                            <td>{{ $equipo->codigo }}</td>

                            <td>{{ $equipo->nombre }}</td>

                            <td>{{ $equipo->categoria }}</td>

                            <td>
                                @if ($equipo->estado === 'Disponible')
                                    <span class="badge bg-success">
                                        Disponible
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        Prestado
                                    </span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('equipos.edit', $equipo) }}"
                                   class="btn btn-sm btn-warning">
                                    Editar
                                </a>

                                <form action="{{ route('equipos.destroy', $equipo) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Deseas eliminar este equipo?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-sm btn-danger">
                                        Eliminar
                                    </button>

                                </form>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="text-center">
                                No hay equipos registrados.
                            </td>
                        </tr>

                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

@endsection