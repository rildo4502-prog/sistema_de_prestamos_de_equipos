@extends('layouts.app')

@section('title', 'Registrar Equipo')

@section('content')

{{-- ENCABEZADO --}}
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Registrar Equipo</h1>

    <p class="text-secondary mb-0">
        Ingresa los datos del nuevo equipo que será registrado en el sistema.
    </p>
</div>


<div class="row justify-content-center">

    <div class="col-lg-8">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-header bg-white border-0 p-4 pb-2">

                <h2 class="h5 fw-bold mb-1">
                    Información del equipo
                </h2>

                <p class="text-secondary small mb-0">
                    Completa todos los campos solicitados.
                </p>

            </div>


            <div class="card-body p-4">

                {{-- ERRORES --}}
                @if ($errors->any())

                    <div class="alert alert-danger">

                        <strong>
                            No se pudo registrar el equipo.
                        </strong>

                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                    </div>

                @endif


                <form action="{{ route('equipos.store') }}" method="POST">
                    @csrf


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
                            value="{{ old('codigo') }}"
                            class="form-control"
                            placeholder="Ejemplo: LAP-004"
                            required
                        >

                        <div class="form-text">
                            Ingresa un código único para identificar el equipo.
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
                            value="{{ old('nombre') }}"
                            class="form-control"
                            placeholder="Ejemplo: Laptop Dell Latitude"
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
                            value="{{ old('categoria') }}"
                            class="form-control"
                            placeholder="Ejemplo: Laptop"
                            required
                        >

                    </div>


                    {{-- BOTONES --}}
                    <div class="d-flex flex-wrap gap-2 pt-2">

                        <button type="submit"
                                class="btn btn-primary px-4">
                            Guardar equipo
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