@extends('layouts.app')

@section('title', 'Gestión de Equipos')

@section('content')

{{-- ENCABEZADO --}}
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1">Gestión de Equipos</h1>

        <p class="text-secondary mb-0">
            Administra los equipos registrados en el sistema.
        </p>
    </div>

    <a href="{{ route('equipos.create') }}"
       class="btn btn-primary px-4">
        Nuevo equipo
    </a>
</div>


{{-- RESUMEN --}}
<div class="row g-3 mb-4">

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">

                <p class="text-secondary small mb-1">
                    Total de equipos
                </p>

                <p class="h2 fw-bold mb-0">
                    {{ $equipos->count() }}
                </p>

            </div>
        </div>
    </div>


    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">

                <p class="text-secondary small mb-1">
                    Disponibles
                </p>

                <p class="h2 fw-bold text-success mb-0">
                    {{ $equipos->where('estado', 'Disponible')->count() }}
                </p>

            </div>
        </div>
    </div>


    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">

                <p class="text-secondary small mb-1">
                    Prestados
                </p>

                <p class="h2 fw-bold text-warning mb-0">
                    {{ $equipos->where('estado', 'Prestado')->count() }}
                </p>

            </div>
        </div>
    </div>

</div>


{{-- TABLA --}}
<div class="card border-0 shadow-sm rounded-4">

    <div class="card-header bg-white border-0 p-4 pb-2">

        <h2 class="h5 fw-bold mb-1">
            Equipos registrados
        </h2>

        <p class="text-secondary small mb-0">
            Consulta, edita o elimina los equipos disponibles.
        </p>

    </div>


    <div class="card-body p-4">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>

                </thead>


                <tbody>

                    @forelse ($equipos as $equipo)

                        <tr>

                            <td>
                                <span class="font-monospace fw-semibold">
                                    {{ $equipo->codigo }}
                                </span>
                            </td>

                            <td class="fw-semibold">
                                {{ $equipo->nombre }}
                            </td>

                            <td>
                                <span class="text-secondary">
                                    {{ $equipo->categoria }}
                                </span>
                            </td>

                            <td>

                                @if ($equipo->estado === 'Disponible')

                                    <span class="badge bg-success-subtle text-success-emphasis px-3 py-2">
                                        Disponible
                                    </span>

                                @else

                                    <span class="badge bg-warning-subtle text-warning-emphasis px-3 py-2">
                                        Prestado
                                    </span>

                                @endif

                            </td>


                            <td class="text-end">

                                <a href="{{ route('equipos.edit', $equipo) }}"
                                   class="btn btn-sm btn-outline-primary me-1">
                                    Editar
                                </a>


                                <form action="{{ route('equipos.destroy', $equipo) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Deseas eliminar este equipo?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger">
                                        Eliminar
                                    </button>

                                </form>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="5"
                                class="text-center text-secondary py-5">

                                <div class="fs-5 mb-1">
                                    No hay equipos registrados
                                </div>

                                <small>
                                    Registra un nuevo equipo para comenzar.
                                </small>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection