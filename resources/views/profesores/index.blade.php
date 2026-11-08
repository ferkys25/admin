@extends('layouts.app')

@section('title', 'Gestión de Profesores')

@section('content')
<!-- Encabezado con el titulo y el boton para registrar nuevo profesor -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">
            <i class="bi bi-person-video3 text-danger me-2"></i>Gestión de Profesores
        </h3>
        <p class="text-muted mb-0">Cuerpo docente habilitado para dictar materias en la universidad.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('profesores.create') }}" class="btn btn-udla shadow-sm">
            <i class="bi bi-person-plus me-1"></i> Registrar Nuevo Profesor
        </a>
    </div>
</div>

<!-- Formulario para buscar profesores por nombre, cedula o correo -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="{{ route('profesores.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-6 col-lg-5">
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Buscar por apellido, nombre, cédula o correo..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-secondary">Buscar</button>
                @if($search)
                    <a href="{{ route('profesores.index') }}" class="btn btn-link text-decoration-none">Limpiar</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Tabla con el listado de todos los profesores -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4">Profesor / Cédula</th>
                        <th>Correo Institucional</th>
                        <th>Materias que puede dictar</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Mostramos la lista de profesores de la base de datos -->
                    @forelse($profesores as $profesor)
                        <tr>
                            <!-- Nombre y cedula validada -->
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $profesor->nombre_completo }}</div>
                                <div class="badge bg-secondary bg-opacity-10 text-dark border">
                                    <i class="bi bi-credit-card-2-front me-1 text-primary"></i>C.I.: {{ $profesor->cedula }}
                                </div>
                            </td>
                            <!-- Correo del docente -->
                            <td>
                                <a href="mailto:{{ $profesor->correo }}" class="text-decoration-none">
                                    <i class="bi bi-envelope me-1 text-muted"></i>{{ $profesor->correo }}
                                </a>
                            </td>
                            <!-- Etiquetas con las materias asociadas en la tabla pivote -->
                            <td>
                                @if($profesor->materias->isNotEmpty())
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($profesor->materias as $mat)
                                            <span class="badge bg-light text-dark border" title="{{ $mat->nombre }}">
                                                {{ $mat->codigo }} - {{ Str::limit($mat->nombre, 18) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-warning small fst-italic">
                                        <i class="bi bi-exclamation-triangle"></i> Sin materias asignadas
                                    </span>
                                @endif
                            </td>
                            <!-- Botones para editar o eliminar al profesor -->
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('profesores.edit', $profesor) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('profesores.destroy', $profesor) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar al profesor {{ $profesor->nombre_completo }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <!-- Mensaje si la busqueda no encuentra registros -->
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-2"></i>
                                No se encontraron profesores registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <!-- Paginacion si hay muchos registros -->
    @if($profesores->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $profesores->links() }}
        </div>
    @endif
</div>
@endsection
