@extends('layouts.guest')

@section('titulo', 'Iniciar sesión')
@section('subtitulo', 'Inicia sesión para continuar')

@section('content')

    <form method="POST" action="{{ route('login.store') }}" novalidate>
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Correo electrónico</label>
            <input id="email"
                   name="email"
                   type="email"
                   value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror"
                   required
                   autofocus
                   autocomplete="username"
                   placeholder="usuario@lab.test">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">Contraseña</label>
            <input id="password"
                   name="password"
                   type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   required
                   autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1">
            <label class="form-check-label small" for="remember">Recordar mi sesión</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-box-arrow-in-right me-1"></i>Entrar
        </button>
    </form>

@endsection
