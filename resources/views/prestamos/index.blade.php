@extends('layouts.app')

@section('title', 'Gestión de Préstamos')

@section('content')

{{-- ENCABEZADO --}}
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold mb-1">Gestión de Préstamos</h1>

        <p class="text-secondary mb-0">
            Registra préstamos y controla la devolución de los equipos.
        </p>
    </div>
</div>


{{-- FORMULARIO PARA REGISTRAR PRÉSTAMO --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">

    <div class="card-header bg-white border-0 p-4 pb-2">

        <h2 class="h5 fw-bold mb-1">
            Registrar nuevo préstamo
        </h2>

        <p class="text-secondary small mb-0">
            Selecciona un equipo disponible y el estudiante que lo recibirá.
        </p>

    </div>


    <div class="card-body p-4">

        {{-- ERRORES DE VALIDACIÓN --}}
        @if ($errors->any())

            <div class="alert alert-danger">

                <strong>No se pudo registrar el préstamo.</strong>

                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        @endif


        <form action="{{ route('prestamos.prestar') }}" method="POST">
            @csrf

            <div class="row g-3 align-items-end">

                {{-- EQUIPO --}}
                <div class="col-lg-5">

                    <label for="equipo_id"
                           class="form-label fw-semibold">
                        Equipo disponible
                    </label>

                    <select
                        name="equipo_id"
                        id="equipo_id"
                        class="form-select"
                        required
                    >
                        <option value="">
                            Seleccione un equipo
                        </option>

                        @foreach ($equipos as $equipo)

                            <option
                                value="{{ $equipo->id }}"
                                @selected(old('equipo_id') == $equipo->id)
                            >
                                {{ $equipo->codigo }} - {{ $equipo->nombre }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- ESTUDIANTE --}}
                <div class="col-lg-5">

                    <label for="user_id"
                           class="form-label fw-semibold">
                        Estudiante
                    </label>

                    <select
                        name="user_id"
                        id="user_id"
                        class="form-select"
                        required
                    >
                        <option value="">
                            Seleccione un estudiante
                        </option>

                        @foreach ($estudiantes as $estudiante)

                            <option
                                value="{{ $estudiante->id }}"
                                @selected(old('user_id') == $estudiante->id)
                            >
                                {{ $estudiante->name }} - {{ $estudiante->email }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- BOTÓN --}}
                <div class="col-lg-2">

                    <button
                        type="submit"
                        class="btn btn-primary w-100"
                    >
                        Prestar equipo
                    </button>

                </div>

            </div>

        </form>

    </div>
</div>


{{-- HISTORIAL --}}
<div class="card border-0 shadow-sm rounded-4">

    <div class="card-header bg-white border-0 p-4 pb-2">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

            <div>

                <h2 class="h5 fw-bold mb-1">
                    Historial de préstamos
                </h2>

                <p class="text-secondary small mb-0">
                    Consulta los préstamos registrados y su estado actual.
                </p>

            </div>

            <span class="badge bg-primary-subtle text-primary-emphasis px-3 py-2">
                {{ $prestamos->count() }} registrados
            </span>

        </div>

    </div>


    <div class="card-body p-4">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>
                        <th>Equipo</th>
                        <th>Estudiante</th>
                        <th>Fecha préstamo</th>
                        <th>Fecha devolución</th>
                        <th>Estado</th>
                        <th class="text-end">Acción</th>
                    </tr>

                </thead>


                <tbody>

                    @forelse ($prestamos as $prestamo)

                        <tr>

                            {{-- EQUIPO --}}
                            <td>

                                <div class="fw-semibold">
                                    {{ $prestamo->equipo->nombre }}
                                </div>

                                <small class="text-secondary font-monospace">
                                    {{ $prestamo->equipo->codigo }}
                                </small>

                            </td>


                            {{-- ESTUDIANTE --}}
                            <td>
                                {{ $prestamo->estudiante->name }}
                            </td>


                            {{-- FECHA PRÉSTAMO --}}
                            <td>
                                {{ $prestamo->fecha_prestamo->format('d/m/Y') }}
                            </td>


                            {{-- FECHA DEVOLUCIÓN --}}
                            <td>

                                @if ($prestamo->fecha_devolucion)

                                    {{ $prestamo->fecha_devolucion->format('d/m/Y') }}

                                @else

                                    <span class="text-secondary">
                                        Pendiente
                                    </span>

                                @endif

                            </td>


                            {{-- ESTADO --}}
                            <td>

                                @if ($prestamo->fecha_devolucion)

                                    <span class="badge bg-success-subtle text-success-emphasis px-3 py-2">
                                        Devuelto
                                    </span>

                                @else

                                    <span class="badge bg-warning-subtle text-warning-emphasis px-3 py-2">
                                        Prestado
                                    </span>

                                @endif

                            </td>


                            {{-- ACCIÓN --}}
                            <td class="text-end">

                                @if (!$prestamo->fecha_devolucion)

                                    <form
                                        action="{{ route('prestamos.devolver', $prestamo) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('¿Confirmar devolución del equipo?')"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-success"
                                        >
                                            Devolver
                                        </button>

                                    </form>

                                @else

                                    <span class="text-secondary">
                                        —
                                    </span>

                                @endif

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="6"
                                class="text-center text-secondary py-5">

                                <div class="fs-5 mb-1">
                                    No hay préstamos registrados
                                </div>

                                <small>
                                    Los nuevos préstamos aparecerán aquí.
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