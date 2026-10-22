@extends('layouts.app')

@section('title', 'Panel')

@section('content')

{{-- BIENVENIDA --}}
<div class="p-4 p-md-5 mb-4 bg-primary text-white rounded-4 shadow-sm">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <p class="mb-1 opacity-75">Sistema de Préstamo de Equipos</p>

            <h1 class="fw-bold mb-2">
                ¡Hola, {{ auth()->user()->name }}!
            </h1>

            <p class="mb-0">
                Bienvenido al panel de
                <span class="badge bg-white text-primary ms-1">
                    {{ ucfirst(auth()->user()->rol) }}
                </span>
            </p>
        </div>

        <div class="d-none d-md-block text-center">
            <i class="bi bi-laptop display-3"></i>
        </div>
    </div>
</div>


{{-- MENSAJE DE ERROR --}}
@if ($error)
    <div class="alert alert-danger shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        {{ $error }}
    </div>
@endif


{{-- PANEL DEL ENCARGADO --}}
@if (auth()->user()->esEncargado())

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h5 fw-bold mb-0">
            Resumen general
        </h2>

        <span class="text-secondary small">
            Estado actual del sistema
        </span>
    </div>


    {{-- TARJETAS --}}
    <div class="row g-3 mb-4">

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-secondary small mb-2">
                                Equipos registrados
                            </p>

                            <p class="display-6 fw-bold mb-0">
                                {{ $totales['equipos'] }}
                            </p>
                        </div>

                        <div class="bg-primary-subtle text-primary rounded-3 p-3">
                            <i class="bi bi-pc-display fs-4"></i>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-secondary small mb-2">
                                Disponibles
                            </p>

                            <p class="display-6 fw-bold text-success mb-0">
                                {{ $totales['disponibles'] }}
                            </p>
                        </div>

                        <div class="bg-success-subtle text-success rounded-3 p-3">
                            <i class="bi bi-check-circle fs-4"></i>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-secondary small mb-2">
                                Prestados
                            </p>

                            <p class="display-6 fw-bold text-warning mb-0">
                                {{ $totales['prestados'] }}
                            </p>
                        </div>

                        <div class="bg-warning-subtle text-warning rounded-3 p-3">
                            <i class="bi bi-arrow-left-right fs-4"></i>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-secondary small mb-2">
                                Préstamos activos
                            </p>

                            <p class="display-6 fw-bold text-primary mb-0">
                                {{ $totales['prestamos_activos'] }}
                            </p>
                        </div>

                        <div class="bg-primary-subtle text-primary rounded-3 p-3">
                            <i class="bi bi-journal-check fs-4"></i>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>


    {{-- ACCESOS RÁPIDOS --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">

            <h2 class="h5 fw-bold mb-1">
                Accesos rápidos
            </h2>

            <p class="text-secondary small mb-3">
                Administra los equipos y préstamos del sistema.
            </p>

            <div class="d-flex flex-wrap gap-2">

                <a href="{{ route('equipos.index') }}"
                   class="btn btn-primary px-4">
                    <i class="bi bi-pc-display me-2"></i>
                    Gestionar equipos
                </a>

                <a href="{{ route('prestamos.index') }}"
                   class="btn btn-success px-4">
                    <i class="bi bi-arrow-left-right me-2"></i>
                    Gestionar préstamos
                </a>

            </div>

        </div>
    </div>

@endif


{{-- PRÉSTAMOS ACTIVOS --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">

    <div class="card-header bg-white border-0 p-4 pb-2">
        <h2 class="h5 fw-bold mb-1">
            <i class="bi bi-journal-check text-primary me-2"></i>

            @if (auth()->user()->esEncargado())
                Préstamos activos del sistema
            @else
                Mis préstamos activos
            @endif
        </h2>

        <p class="text-secondary small mb-0">
            Consulta los equipos que actualmente se encuentran prestados.
        </p>
    </div>


    <div class="card-body p-4">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
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
                            <td class="fw-semibold">
                                {{ $prestamo->equipo?->nombre ?? 'Equipo eliminado' }}
                            </td>

                            <td>
                                <span class="font-monospace">
                                    {{ $prestamo->equipo?->codigo ?? '—' }}
                                </span>
                            </td>

                            <td>
                                {{ $prestamo->fecha_prestamo?->format('d/m/Y') ?? '—' }}
                            </td>

                            <td>
                                <span class="badge bg-warning-subtle text-warning-emphasis">
                                    Prestado
                                </span>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="4"
                                class="text-center text-secondary py-5">

                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>

                                No hay préstamos activos por mostrar.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>
</div>


{{-- PANEL DEL ESTUDIANTE --}}
@if (auth()->user()->esEstudiante())

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 p-4 pb-2">

            <h2 class="h5 fw-bold mb-1">
                <i class="bi bi-check-circle text-success me-2"></i>
                Equipos disponibles
            </h2>

            <p class="text-secondary small mb-0">
                Puedes consultar los equipos disponibles para préstamo.
            </p>

        </div>


        <div class="card-body p-4">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
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
                                    <span class="font-monospace">
                                        {{ $equipo->codigo }}
                                    </span>
                                </td>

                                <td class="fw-semibold">
                                    {{ $equipo->nombre }}
                                </td>

                                <td>
                                    {{ $equipo->categoria }}
                                </td>

                                <td>
                                    <span class="badge bg-success-subtle text-success-emphasis">
                                        Disponible
                                    </span>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4"
                                    class="text-center text-secondary py-5">

                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>

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