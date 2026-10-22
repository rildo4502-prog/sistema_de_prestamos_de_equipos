@extends('layouts.app')

@section('title', 'Registrar Equipo')

@section('content')

<h2 class="mb-4">Registrar Equipo</h2>

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

        <form action="{{ route('equipos.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="codigo" class="form-label">Código</label>
                <input
                    type="text"
                    id="codigo"
                    name="codigo"
                    value="{{ old('codigo') }}"
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
                    value="{{ old('nombre') }}"
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
                    value="{{ old('categoria') }}"
                    class="form-control"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary">
                Guardar
            </button>

            <a href="{{ route('equipos.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </form>

    </div>
</div>

@endsection