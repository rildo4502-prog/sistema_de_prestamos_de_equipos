<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('titulo', 'Panel') · {{ config('app.name', 'Préstamos de Equipos') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">

            <button class="navbar-toggler d-lg-none me-2" type="button"
                    data-bs-toggle="offcanvas" data-bs-target="#sidebarPanel"
                    aria-controls="sidebarPanel" aria-label="Abrir menú">
                <span class="navbar-toggler-icon"></span>
            </button>

            <a class="navbar-brand fw-semibold" href="{{ Route::has('panel') ? route('panel') : url('/') }}">
                <i class="bi bi-box-seam me-2"></i>Préstamos de Equipos
            </a>

            <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navbarContenido"
                    aria-controls="navbarContenido" aria-expanded="false" aria-label="Alternar navegación">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarContenido">
                @auth
                    <ul class="navbar-nav align-items-lg-center gap-lg-3">

                        <li class="nav-item d-none d-lg-block">
                            <span class="navbar-text">
                                <span class="fw-semibold">{{ auth()->user()->name }}</span>
                                <span class="badge ms-2 text-bg-{{ auth()->user()->esEncargado() ? 'primary' : 'success' }}">
                                    {{ auth()->user()->rol }}
                                </span>
                            </span>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#"
                               role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle fs-5"></i>
                                <span class="d-lg-none">{{ auth()->user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li class="px-3 py-2">
                                    <div class="fw-semibold">{{ auth()->user()->name }}</div>
                                    <div class="small text-secondary">{{ auth()->user()->email }}</div>
                                    <span class="badge mt-1 text-bg-{{ auth()->user()->esEncargado() ? 'primary' : 'success' }}">
                                        {{ auth()->user()->rol }}
                                    </span>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                @else
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Iniciar sesión
                            </a>
                        </li>
                    </ul>
                @endauth
            </div>
        </div>
    </nav>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarPanel"
         aria-labelledby="sidebarPanelTitulo">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="sidebarPanelTitulo">Menú</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
        </div>
        <div class="offcanvas-body">
            @auth
                @include('partials.menu')
            @endauth
        </div>
    </div>

    <div class="container-fluid" style="padding-top: 4rem;">
        <div class="row">

            <aside class="col-lg-3 col-xl-2 d-none d-lg-block px-3 py-4">
                <div class="sidebar-panel">
                    @auth
                        @include('partials.menu')
                    @endauth
                </div>
            </aside>

            <main class="col-lg-9 col-xl-10 py-4">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-warning" role="alert">
                        <h6 class="alert-heading"><i class="bi bi-exclamation-triangle me-2"></i>Revisa los datos ingresados</h6>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
