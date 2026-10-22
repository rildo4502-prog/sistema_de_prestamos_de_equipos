@extends('layouts.app')

@section('title', 'Editar Equipo')

@section('content')

<h2 class="mb-4">Editar Equipo</h2>

<div class="card">
    <div class="card-body">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('equipos.update', $equipo) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="codigo" class="form-label">Código</label>
                <input
                    type="text"
                    id="codigo"
                    name="codigo"
                    value="{{ old('codigo', $equipo->codigo) }}"
                    class="form-control"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    value="{{ old('nombre', $equipo->nombre) }}"
                    class="form-control"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="categoria" class="form-label">Categoría</label>
                <input
                    type="text"
                    id="categoria"
                    name="categoria"
                    value="{{ old('categoria', $equipo->categoria) }}"
                    class="form-control"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary">
                Actualizar
            </button>

            <a href="{{ route('equipos.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </form>

    </div>
</div>

@endsection