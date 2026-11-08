@extends('layouts.app')

@section('title', 'Panel de Control')

@section('content')
<!-- Encabezado de la seccion principal -->
<div class="row align-items-center mb-4">
    <div class="col-md-8">
        <h3 class="fw-bold text-dark mb-1">
            <i class="bi bi-speedometer2 text-danger me-2"></i>Panel de Control
        </h3>
        <p class="text-muted mb-0">Resumen de los recursos academicos registrados en el sistema.</p>
    </div>
    <!-- Boton de acceso directo para crear una asignacion -->
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="{{ route('paralelos.create') }}" class="btn btn-udla shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Nueva Asignación / Paralelo
        </a>
    </div>
</div>

<!-- Tarjetas con las estadisticas y totales -->
<div class="row g-3 mb-4">
    <!-- Tarjeta total de profesores -->
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3 fs-3">
                    <i class="bi bi-person-video3"></i>
                </div>
                <div>
                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Profesores</h6>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['total_profesores'] }}</h3>
                    <small class="text-success"><i class="bi bi-check-circle"></i> Registrados</small>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('profesores.index') }}" class="small text-decoration-none text-primary fw-semibold">
                    Ver todos los profesores <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Tarjeta total de materias -->
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-success bg-opacity-10 text-success p-3 rounded-3 fs-3">
                    <i class="bi bi-book"></i>
                </div>
                <div>
                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Materias</h6>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['total_materias'] }}</h3>
                    <small class="text-muted">En la malla</small>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('materias.index') }}" class="small text-decoration-none text-success fw-semibold">
                    Ver catálogo de materias <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Tarjeta total de periodos academicos -->
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-3 fs-3">
                    <i class="bi bi-calendar3"></i>
                </div>
                <div>
                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Períodos</h6>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['total_periodos'] }}</h3>
                    <small class="text-muted">Ciclos lectivos</small>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('periodos.index') }}" class="small text-decoration-none text-warning fw-semibold">
                    Ver períodos académicos <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Tarjeta total de paralelos creados -->
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-3 fs-3">
                    <i class="bi bi-diagram-3"></i>
                </div>
                <div>
                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Paralelos</h6>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['total_paralelos'] }}</h3>
                    <small class="text-muted">Asignados</small>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('paralelos.index') }}" class="small text-decoration-none text-danger fw-semibold">
                    Ver lista de paralelos <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Alerta si hay materias que todavia no tienen profesores vinculados -->
@if($materiasSinProfesor > 0)
    <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center gap-3 mb-4">
        <i class="bi bi-exclamation-circle fs-3 text-warning"></i>
        <div>
            <h6 class="mb-0 fw-bold">Atención: Hay materias sin profesores asignados</h6>
            <small>Para que un profesor aparezca en la lista de una materia, debes habilitarlo primero en la seccion de profesores.</small>
        </div>
        <a href="{{ route('profesores.index') }}" class="btn btn-sm btn-warning ms-auto fw-bold">Asignar Materias</a>
    </div>
@endif

<!-- Tabla con los ultimos paralelos creados en el sistema -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-clock-history me-1 text-primary"></i> Últimos Paralelos Registrados</h6>
        <a href="{{ route('paralelos.create') }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-plus-lg"></i> Asignar Paralelo
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4">Paralelo</th>
                        <th>Materia</th>
                        <th>Período</th>
                        <th>Profesor Habilitado</th>
                        <th>Tipo Aula</th>
                        <th class="text-end pe-4">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Recorremos los ultimos paralelos guardados -->
                    @forelse($ultimosParalelos as $paralelo)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">
                                <span class="badge bg-light text-dark border px-2 py-1">Paralelo {{ $paralelo->numero }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $paralelo->materia->nombre }}</div>
                                <small class="text-muted">{{ $paralelo->materia->codigo }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info fw-semibold">{{ $paralelo->periodo->nombre }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-person-circle text-secondary"></i>
                                    <div>
                                        <div class="fw-semibold text-dark">{{ $paralelo->profesor->nombre_completo }}</div>
                                        <small class="text-muted">C.I.: {{ $paralelo->profesor->cedula }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                    {{ ucfirst($paralelo->materia->tipo_aula) }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('paralelos.edit', $paralelo) }}" class="btn btn-sm btn-light text-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <!-- Mensaje si todavia no hay registros -->
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                No hay paralelos asignados aún. <a href="{{ route('paralelos.create') }}">Crear el primero</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
