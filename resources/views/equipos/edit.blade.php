@extends('layouts.app')

@section('title', 'Editar Equipo')

@section('content')

{{-- ENCABEZADO --}}
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Editar Equipo</h1>

    <p class="text-secondary mb-0">
        Modifica la información del equipo seleccionado.
    </p>
</div>


<div class="row justify-content-center">

    <div class="col-lg-8">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-header bg-white border-0 p-4 pb-2">

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                    <div>
                        <h2 class="h5 fw-bold mb-1">
                            Información del equipo
                        </h2>

                        <p class="text-secondary small mb-0">
                            Actualiza los datos que necesites modificar.
                        </p>
                    </div>

                    <span class="badge bg-primary-subtle text-primary-emphasis px-3 py-2">
                        {{ $equipo->codigo }}
                    </span>

                </div>

            </div>


            <div class="card-body p-4">

                {{-- ERRORES --}}
                @if ($errors->any())

                    <div class="alert alert-danger">

                        <strong>
                            No se pudo actualizar el equipo.
                        </strong>

                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                    </div>

                @endif


                <form action="{{ route('equipos.update', $equipo) }}"
                      method="POST">

                    @csrf
                    @method('PUT')


                    {{-- CÓDIGO --}}
                    <div class="mb-4">

                        <label for="codigo"
                               class="form-label fw-semibold">
                            Código
                        </label>

                        <input
                            type="text"
                            id="codigo"
                            name="codigo"
                            value="{{ old('codigo', $equipo->codigo) }}"
                            class="form-control"
                            required
                        >

                        <div class="form-text">
                            El código debe ser único para cada equipo.
                        </div>

                    </div>


                    {{-- NOMBRE --}}
                    <div class="mb-4">

                        <label for="nombre"
                               class="form-label fw-semibold">
                            Nombre
                        </label>

                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            value="{{ old('nombre', $equipo->nombre) }}"
                            class="form-control"
                            required
                        >

                    </div>


                    {{-- CATEGORÍA --}}
                    <div class="mb-4">

                        <label for="categoria"
                               class="form-label fw-semibold">
                            Categoría
                        </label>

                        <input
                            type="text"
                            id="categoria"
                            name="categoria"
                            value="{{ old('categoria', $equipo->categoria) }}"
                            class="form-control"
                            required
                        >

                    </div>


                    {{-- BOTONES --}}
                    <div class="d-flex flex-wrap gap-2 pt-2">

                        <button type="submit"
                                class="btn btn-primary px-4">
                            Guardar cambios
                        </button>

                        <a href="{{ route('equipos.index') }}"
                           class="btn btn-outline-secondary px-4">
                            Cancelar
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection