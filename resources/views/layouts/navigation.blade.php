<nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm">
    <div class="container">

        {{-- Nombre del sistema --}}
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2"
           href="{{ route('panel') }}">
            <span class="text-primary">◆</span>
            Préstamo de Equipos
        </a>

        {{-- Botón responsive --}}
        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarPrincipal"
                aria-controls="navbarPrincipal"
                aria-expanded="false"
                aria-label="Abrir menú">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-collapse" id="navbarPrincipal">
            {{-- Navegación izquierda --}}
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('panel') ? 'active fw-semibold' : '' }}"
                       href="{{ route('panel') }}">
                        Panel
                    </a>
                </li>

                {{-- Solo Encargado --}}
                @if (auth()->user()->esEncargado())

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('equipos.*') ? 'active fw-semibold' : '' }}"
                           href="{{ route('equipos.index') }}">
                            Equipos
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('prestamos.*') ? 'active fw-semibold' : '' }}"
                           href="{{ route('prestamos.index') }}">
                            Préstamos
                        </a>
                    </li>

                @endif

            </ul>


            {{-- Usuario --}}
            <div class="dropdown">

                <button class="btn btn-light border dropdown-toggle"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">

                    <strong>{{ auth()->user()->name }}</strong>

                    <span class="badge ms-1 text-bg-{{ auth()->user()->esEncargado() ? 'primary' : 'success' }}">
                        {{ ucfirst(auth()->user()->rol) }}
                    </span>

                </button>


                <ul class="dropdown-menu dropdown-menu-end shadow-sm">

                    <li class="px-3 py-2">
                        <small class="text-secondary">
                            Sesión iniciada como
                        </small>

                        <div class="fw-semibold">
                            {{ auth()->user()->email }}
                        </div>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item"
                           href="{{ route('profile.edit') }}">
                            Mi perfil
                        </a>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <form method="POST"
                              action="{{ route('logout') }}">
                            @csrf

                            <button type="submit"
                                    class="dropdown-item text-danger">
                                Cerrar sesión
                            </button>
                        </form>
                    </li>

                </ul>

            </div>

        </div>
    </div>
</nav>