@extends('layouts.app')

@section('title', 'Panel')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h1 class="h3 mb-1">Panel</h1>

        <p class="text-secondary mb-0">
            Bienvenido, <strong>{{ auth()->user()->name }}</strong>

            <span class="badge ms-1 text-bg-{{ auth()->user()->esEncargado() ? 'primary' : 'success' }}">
                {{ ucfirst(auth()->user()->rol) }}
            </span>
        </p>
    </div>
</div>


{{-- MENSAJE DE ERROR --}}
@if ($error)
    <div class="alert alert-danger" role="alert">
        {{ $error }}
    </div>
@endif


{{-- ========================= --}}
{{-- PANEL DEL ENCARGADO --}}
{{-- ========================= --}}

@if (auth()->user()->esEncargado())

    <div class="row g-3 mb-4">

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-secondary small mb-1">
                        Equipos registrados
                    </p>

                    <p class="h3 mb-0">
                        {{ $totales['equipos'] }}
                    </p>
                </div>
            </div>
        </div>


        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-secondary small mb-1">
                        Disponibles
                    </p>

                    <p class="h3 mb-0">
                        {{ $totales['disponibles'] }}
                    </p>
                </div>
            </div>
        </div>


        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-secondary small mb-1">
                        Prestados
                    </p>

                    <p class="h3 mb-0">
                        {{ $totales['prestados'] }}
                    </p>
                </div>
            </div>
        </div>


        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-secondary small mb-1">
                        Préstamos activos
                    </p>

                    <p class="h3 mb-0">
                        {{ $totales['prestamos_activos'] }}
                    </p>
                </div>
            </div>
        </div>

    </div>


    {{-- ACCESOS DEL ENCARGADO --}}

    <div class="mb-4">

        <a href="{{ route('equipos.index') }}"
           class="btn btn-primary">
            Gestionar equipos
        </a>

        <a href="{{ route('prestamos.index') }}"
           class="btn btn-success">
            Gestionar préstamos
        </a>

    </div>

@endif



{{-- ========================= --}}
{{-- PRÉSTAMOS DEL USUARIO --}}
{{-- ========================= --}}

<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white">

        <h2 class="h6 mb-0">

            @if (auth()->user()->esEncargado())
                Préstamos activos del sistema
            @else
                Mis préstamos activos
            @endif

        </h2>

    </div>


    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>

                    <tr>
                        <th>Equipo</th>
                        <th>Código</th>
                        <th>Fecha de préstamo</th>
                        <th>Estado</th>
                    </tr>

                </thead>


                <tbody>

                    @forelse ($misPrestamos as $prestamo)

                        <tr>

                            <td>
                                {{ $prestamo->equipo?->nombre ?? 'Equipo eliminado' }}
                            </td>

                            <td>
                                {{ $prestamo->equipo?->codigo ?? '—' }}
                            </td>

                            <td>
                                {{ $prestamo->fecha_prestamo?->format('d/m/Y') ?? '—' }}
                            </td>

                            <td>
                                <span class="badge bg-warning text-dark">
                                    Prestado
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4"
                                class="text-center text-secondary">

                                No hay préstamos activos por mostrar.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>



{{-- ========================= --}}
{{-- PANEL DEL ESTUDIANTE --}}
{{-- ========================= --}}

@if (auth()->user()->esEstudiante())

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <h2 class="h6 mb-0">
                Equipos disponibles
            </h2>

        </div>


        <div class="card-body">

            <p class="text-secondary">
                Puedes consultar los equipos que actualmente se encuentran disponibles.
            </p>


            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>
                            <th>Código</th>
                            <th>Equipo</th>
                            <th>Categoría</th>
                            <th>Estado</th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($equiposDisponibles as $equipo)

                            <tr>

                                <td>
                                    {{ $equipo->codigo }}
                                </td>

                                <td>
                                    {{ $equipo->nombre }}
                                </td>

                                <td>
                                    {{ $equipo->categoria }}
                                </td>

                                <td>
                                    <span class="badge bg-success">
                                        Disponible
                                    </span>
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4"
                                    class="text-center text-secondary">

                                    No hay equipos disponibles.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endif


@endsection