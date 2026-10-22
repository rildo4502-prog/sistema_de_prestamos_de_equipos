@extends('layouts.app')

@section('title', 'Gestión de Préstamos')

@section('content')

<h2 class="mb-4">Gestión de Préstamos</h2>

{{-- FORMULARIO PARA REGISTRAR UN PRÉSTAMO --}}
<div class="card mb-4">
    <div class="card-body">

        <h4>Registrar nuevo préstamo</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('prestamos.prestar') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="equipo_id" class="form-label">
                    Equipo disponible
                </label>

                <select
                    name="equipo_id"
                    id="equipo_id"
                    class="form-select"
                    required
                >
                    <option value="">Seleccione un equipo</option>

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

            <div class="mb-3">
                <label for="user_id" class="form-label">
                    Estudiante
                </label>

                <select
                    name="user_id"
                    id="user_id"
                    class="form-select"
                    required
                >
                    <option value="">Seleccione un estudiante</option>

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

            <button type="submit" class="btn btn-primary">
                Prestar equipo
            </button>
        </form>

    </div>
</div>


{{-- LISTADO DE PRÉSTAMOS --}}
<div class="card">
    <div class="card-body">

        <h4>Historial de préstamos</h4>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">

                <thead>
                    <tr>
                        <th>Equipo</th>
                        <th>Estudiante</th>
                        <th>Fecha préstamo</th>
                        <th>Fecha devolución</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($prestamos as $prestamo)

                        <tr>
                            <td>
                                {{ $prestamo->equipo->codigo }}
                                -
                                {{ $prestamo->equipo->nombre }}
                            </td>

                            <td>
                                {{ $prestamo->estudiante->name }}
                            </td>

                            <td>
                                {{ $prestamo->fecha_prestamo->format('d/m/Y') }}
                            </td>

                            <td>
                                @if ($prestamo->fecha_devolucion)
                                    {{ $prestamo->fecha_devolucion->format('d/m/Y') }}
                                @else
                                    Pendiente
                                @endif
                            </td>

                            <td>
                                @if ($prestamo->fecha_devolucion)
                                    <span class="badge bg-success">
                                        Devuelto
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        Prestado
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if (!$prestamo->fecha_devolucion)

                                    <form
                                        action="{{ route('prestamos.devolver', $prestamo) }}"
                                        method="POST"
                                        onsubmit="return confirm('¿Confirmar devolución del equipo?')"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="btn btn-success btn-sm"
                                        >
                                            Devolver
                                        </button>
                                    </form>

                                @else
                                    -
                                @endif
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center">
                                No hay préstamos registrados.
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>
        </div>

    </div>
</div>

@endsection