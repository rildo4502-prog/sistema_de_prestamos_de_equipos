<x-guest-layout>

    <div class="text-center mb-4">

        <div class="mb-3">
            <div class="d-inline-flex align-items-center justify-content-center
                        bg-primary text-white rounded-circle"
                 style="width: 60px; height: 60px; font-size: 28px;">
                ◆
            </div>
        </div>

        <h2 class="fw-bold mb-1">
            Préstamo de Equipos
        </h2>

        <p class="text-secondary mb-0">
            Sistema de gestión y control de equipos
        </p>

    </div>


    {{-- Mensaje de sesión --}}
    <x-auth-session-status
        class="mb-4"
        :status="session('status')"
    />


    <form method="POST" action="{{ route('login') }}">
        @csrf


        {{-- Correo --}}
        <div class="mb-3">

            <label for="email"
                   class="form-label fw-semibold">
                Correo electrónico
            </label>

            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror"
                placeholder="ejemplo@correo.com"
                required
                autofocus
                autocomplete="username"
            >

            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>


        {{-- Contraseña --}}
        <div class="mb-3">

            <label for="password"
                   class="form-label fw-semibold">
                Contraseña
            </label>

            <input
                id="password"
                type="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="Ingresa tu contraseña"
                required
                autocomplete="current-password"
            >

            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>


        {{-- Recordarme --}}
        <div class="form-check mb-4">

            <input
                id="remember_me"
                type="checkbox"
                class="form-check-input"
                name="remember"
            >

            <label class="form-check-label"
                   for="remember_me">
                Recordarme
            </label>

        </div>


        {{-- Botón --}}
        <div class="d-grid">

            <button
                type="submit"
                class="btn btn-primary btn-lg">
                Iniciar sesión
            </button>

        </div>

    </form>


    <div class="text-center mt-4">
        <small class="text-secondary">
            Acceso para encargados y estudiantes
        </small>
    </div>

</x-guest-layout>