@extends('layouts.app')

@section('title', 'Gestión de Materias')

@section('content')
<!-- Encabezado y boton para crear una nueva materia -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">
            <i class="bi bi-book text-danger me-2"></i>Gestión de Materias
        </h3>
        <p class="text-muted mb-0">Catálogo de asignaturas de la carrera y requerimientos de aula.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('materias.create') }}" class="btn btn-udla shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Nueva Materia
        </a>
    </div>
</div>

<!-- Buscador de materias por codigo o nombre -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="{{ route('materias.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-6 col-lg-5">
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Buscar por código o nombre..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-secondary">Buscar</button>
                @if($search)
                    <a href="{{ route('materias.index') }}" class="btn btn-link text-decoration-none">Limpiar</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Tabla donde se muestran todas las materias registradas -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4">Código</th>
                        <th>Nombre de la Materia</th>
                        <th>Horas Semanales</th>
                        <th>Tipo de Aula</th>
                        <th>Profesores Habilitados</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Recorremos cada materia del listado -->
                    @forelse($materias as $materia)
                        <tr>
                            <!-- Codigo unico de la asignatura -->
                            <td class="ps-4 fw-bold text-primary font-monospace">{{ $materia->codigo }}</td>
                            <!-- Nombre completo -->
                            <td class="fw-semibold text-dark">{{ $materia->nombre }}</td>
                            <!-- Carga horaria semanal -->
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-clock me-1 text-muted"></i>{{ $materia->horas_semanales }} hrs / semana
                                </span>
                            </td>
                            <!-- Tipo de aula requerida (teorica o laboratorio) -->
                            <td>
                                @if($materia->tipo_aula === 'laboratorio')
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">
                                        <i class="bi bi-cpu me-1"></i>Laboratorio
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border">
                                        <i class="bi bi-easel me-1"></i>Teórica
                                    </span>
                                @endif
                            </td>
                            <!-- Cuantos profesores pueden dar esta materia segun la tabla pivote -->
                            <td>
                                @if($materia->profesores_count > 0)
                                    <span class="badge bg-success bg-opacity-10 text-success">
                                        <i class="bi bi-check-circle me-1"></i>{{ $materia->profesores_count }} docente(s)
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger">
                                        <i class="bi bi-exclamation-triangle me-1"></i>Sin docentes
                                    </span>
                                @endif
                            </td>
                            <!-- Botones para editar o borrar -->
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('materias.edit', $materia) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('materias.destroy', $materia) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar la materia {{ $materia->nombre }}?');">
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
                        <!-- Mensaje si no hay materias registradas -->
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-x fs-1 d-block mb-2"></i>
                                No se encontraron materias registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <!-- Paginacion de materias -->
    @if($materias->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $materias->links() }}
        </div>
    @endif
</div>
@endsection
