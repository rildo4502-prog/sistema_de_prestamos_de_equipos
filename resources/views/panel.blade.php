@extends('layouts.app')

@section('titulo', 'Panel')

@section('content')

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 mb-1">Panel</h1>
            <p class="text-secondary mb-0">
                Bienvenido, <strong>{{ auth()->user()->name }}</strong>
                <span class="badge ms-1 text-bg-{{ auth()->user()->esEncargado() ? 'primary' : 'success' }}">
                    {{ auth()->user()->rol }}
                </span>
            </p>
        </div>
    </div>

    @if ($error)
        <div class="alert alert-danger" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $error }}
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-secondary small mb-1">Equipos registrados</p>
                    <p class="h3 mb-0">{{ $totales['equipos'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-success">
                <div class="card-body">
                    <p class="text-secondary small mb-1">Disponibles</p>
                    <p class="h3 mb-0">{{ $totales['disponibles'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-warning">
                <div class="card-body">
                    <p class="text-secondary small mb-1">Prestados</p>
                    <p class="h3 mb-0">{{ $totales['prestados'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-primary">
                <div class="card-body">
                    <p class="text-secondary small mb-1">Préstamos activos</p>
                    <p class="h3 mb-0">{{ $totales['prestamos_activos'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h2 class="h6 mb-0">
                <i class="bi bi-journal-check me-2"></i>
                {{ auth()->user()->esEncargado() ? 'Préstamos activos del sistema' : 'Mis préstamos activos' }}
            </h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Equipo</th>
                            <th scope="col">Código</th>
                            <th scope="col">Fecha de préstamo</th>
                            <th scope="col">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($misPrestamos as $prestamo)
                            <tr>
                                <td>{{ $prestamo->equipo?->nombre ?? 'Equipo eliminado' }}</td>
                                <td class="font-monospace">{{ $prestamo->equipo?->codigo ?? '—' }}</td>
                                <td>{{ $prestamo->fecha_prestamo?->format('d/m/Y') ?? '—' }}</td>
                                <td>
                                    <span class="badge text-bg-warning">Prestado</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-secondary py-4">
                                    No hay préstamos activos por mostrar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
