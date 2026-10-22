<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('titulo', 'Acceso') · {{ config('app.name', 'Préstamos de Equipos') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-body-secondary">
    <div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
        <div class="card shadow-sm w-100" style="max-width: 26rem;">
            <div class="card-body p-4">

                <div class="text-center mb-4">
                    <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-3 mb-3"
                          style="width: 3rem; height: 3rem;">
                        <i class="bi bi-box-seam fs-4"></i>
                    </span>
                    <h1 class="h4 mb-1">Préstamos de Equipos</h1>
                    <p class="text-secondary small mb-0">@yield('subtitulo', 'Inicia sesión para continuar')</p>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-warning" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
