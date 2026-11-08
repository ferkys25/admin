<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Horarios') - UDLA</title>

    <!-- Cargamos Bootstrap y los iconos desde internet -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        /* Estilos basicos para el color rojo de la universidad */
        .bg-udla {
            background-color: #8a1538 !important;
        }
        .btn-udla {
            background-color: #8a1538;
            color: #ffffff;
            border: none;
        }
        .btn-udla:hover {
            background-color: #6d0f2b;
            color: #ffffff;
        }
        /* Ancho fijo para la barra lateral */
        .sidebar {
            width: 250px;
            min-height: 100vh;
        }
    </style>
</head>
<body class="bg-light">

<div class="d-flex">
    <!-- Barra lateral de navegacion -->
    <div class="sidebar bg-dark text-white d-flex flex-column p-3">
        <!-- Titulo del sistema con icono -->
        <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center mb-3 text-white text-decoration-none">
            <i class="bi bi-mortarboard-fill fs-3 text-danger me-2"></i>
            <span class="fs-5 fw-bold">UDLA Horarios</span>
        </a>
        <hr class="text-secondary">

        <!-- Informacion del usuario conectado y su rol -->
        <div class="mb-3 small">
            <div class="text-secondary">Conectado como:</div>
            <div class="fw-bold">{{ Auth::user()->name ?? 'Usuario' }}</div>
            <span class="badge {{ Auth::user()->role === 'admin' ? 'bg-danger' : 'bg-primary' }}">
                Rol: {{ strtoupper(Auth::user()->role ?? 'Invitado') }}
            </span>
        </div>
        <hr class="text-secondary">

        <!-- Enlaces del menu segun el tipo de usuario -->
        <ul class="nav nav-pills flex-column mb-auto">
            <!-- Si el usuario es admin le mostramos todas las opciones de gestion -->
            @if(Auth::check() && Auth::user()->role === 'admin')
                <li class="nav-item mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'bg-udla active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i> Panel Principal
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ route('profesores.index') }}" class="nav-link text-white {{ request()->routeIs('profesores.*') ? 'bg-udla active' : '' }}">
                        <i class="bi bi-person-video3 me-2"></i> Profesores
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ route('materias.index') }}" class="nav-link text-white {{ request()->routeIs('materias.*') ? 'bg-udla active' : '' }}">
                        <i class="bi bi-book me-2"></i> Materias
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ route('periodos.index') }}" class="nav-link text-white {{ request()->routeIs('periodos.*') ? 'bg-udla active' : '' }}">
                        <i class="bi bi-calendar3 me-2"></i> Períodos
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ route('paralelos.index') }}" class="nav-link text-white {{ request()->routeIs('paralelos.*') ? 'bg-udla active' : '' }}">
                        <i class="bi bi-diagram-3 me-2"></i> Paralelos (Asignación)
                    </a>
                </li>
            @endif

            <!-- Esta opcion la ven todos los usuarios para revisar su horario -->
            <li class="nav-item mb-1">
                <a href="{{ route('mi-horario') }}" class="nav-link text-white {{ request()->routeIs('mi-horario') ? 'bg-udla active' : '' }}">
                    <i class="bi bi-clock me-2"></i> Mi Horario (Lectura)
                </a>
            </li>
        </ul>

        <hr class="text-secondary">

        <!-- Formulario para cerrar la sesion actual -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-light btn-sm w-100">
                <i class="bi bi-box-arrow-right me-1"></i> Cerrar Sesión
            </button>
        </form>
    </div>

    <!-- Zona derecha donde se carga la vista de cada pagina -->
    <div class="flex-grow-1 p-4">
        <!-- Alerta de exito cuando se guarda o actualiza un registro -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Alerta de advertencia cuando no tiene permisos -->
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-1"></i> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Alerta de error -->
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-x-circle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Aqui se inserta el contenido especifico de cada vista -->
        @yield('content')
    </div>
</div>

<!-- Scripts de bootstrap y los scripts de cada pagina -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
