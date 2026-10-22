@php
    $esEncargado = auth()->user()->esEncargado();

    $menu = $esEncargado ? [
        ['route' => 'panel', 'label' => 'Panel', 'icon' => 'bi-speedometer2', 'match' => 'panel'],
        ['route' => 'equipos.index', 'label' => 'Equipos', 'icon' => 'bi-laptop', 'match' => 'equipos.*'],
        ['route' => 'equipos.create', 'label' => 'Registrar equipo', 'icon' => 'bi-plus-square', 'match' => 'equipos.create'],
        ['route' => 'prestamos.index', 'label' => 'Préstamos', 'icon' => 'bi-arrow-left-right', 'match' => 'prestamos.*'],
        ['route' => 'usuarios.index', 'label' => 'Usuarios', 'icon' => 'bi-people', 'match' => 'usuarios.*'],
    ] : [
        ['route' => 'panel', 'label' => 'Panel', 'icon' => 'bi-speedometer2', 'match' => 'panel'],
        ['route' => 'prestamos.mis', 'label' => 'Mis préstamos', 'icon' => 'bi-journal-check', 'match' => 'prestamos.mis'],
        ['route' => 'prestamos.solicitar', 'label' => 'Solicitar equipo', 'icon' => 'bi-plus-square', 'match' => 'prestamos.solicitar'],
        ['route' => 'equipos.disponibles', 'label' => 'Equipos disponibles', 'icon' => 'bi-box-seam', 'match' => 'equipos.disponibles'],
    ];
@endphp

<ul class="nav nav-pills flex-column gap-1">
    @foreach ($menu as $item)
        @continue(! Route::has($item['route']))

        <li class="nav-item">
            <a class="nav-link d-flex align-items-center {{ request()->routeIs($item['match']) ? 'active' : '' }}"
               href="{{ route($item['route']) }}"
               @if (request()->routeIs($item['match'])) aria-current="page" @endif>
                <i class="bi {{ $item['icon'] }} me-2"></i>
                <span>{{ $item['label'] }}</span>
            </a>
        </li>
    @endforeach
</ul>

@auth
    <hr class="my-3">

    <div class="card border-0 bg-transparent">
        <div class="card-body p-0">
            <p class="text-uppercase small text-secondary fw-semibold mb-1">Sesión actual</p>
            <p class="mb-0 fw-semibold">{{ auth()->user()->name }}</p>
            <span class="badge text-bg-{{ auth()->user()->esEncargado() ? 'primary' : 'success' }} mt-1">
                {{ auth()->user()->rol }}
            </span>
        </div>
    </div>
@endauth
