@extends('layouts.app')

@section('title', 'Gestión de Paralelos')

@section('content')
<!-- Encabezado con boton para registrar una nueva asignacion -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">
            <i class="bi bi-diagram-3 text-danger me-2"></i>Gestión de Paralelos / Asignaciones
        </h3>
        <p class="text-muted mb-0">Carga académica vinculando Período, Materia y Docente calificado.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('paralelos.create') }}" class="btn btn-udla shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Asignar Nuevo Paralelo
        </a>
    </div>
</div>

<!-- Filtro para ver los paralelos de un periodo en especifico -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="{{ route('paralelos.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted"><i class="bi bi-filter"></i></span>
                    <select name="periodo_id" class="form-select">
                        <option value="">-- Todos los Períodos --</option>
                        @foreach($periodos as $p)
                            <option value="{{ $p->id }}" {{ $periodoId == $p->id ? 'selected' : '' }}>
                                {{ $p->nombre }} ({{ $p->fecha_inicio ? $p->fecha_inicio->format('Y') : '' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-secondary">Filtrar</button>
                @if($periodoId)
                    <a href="{{ route('paralelos.index') }}" class="btn btn-link text-decoration-none">Limpiar filtro</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Tabla donde se muestran todos los paralelos -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4">Paralelo</th>
                        <th>Materia</th>
                        <th>Período Académico</th>
                        <th>Profesor Asignado</th>
                        <th>Tipo Aula</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Recorremos los paralelos creados -->
                    @forelse($paralelos as $paralelo)
                        <tr>
                            <!-- Numero o codigo del paralelo -->
                            <td class="ps-4 fw-bold text-dark">
                                <span class="badge bg-light text-dark border px-2 py-1">Paralelo {{ $paralelo->numero }}</span>
                            </td>
                            <!-- Materia asociada -->
                            <td>
                                <div class="fw-semibold text-dark">{{ $paralelo->materia->nombre }}</div>
                                <small class="text-primary font-monospace">{{ $paralelo->materia->codigo }}</small>
                            </td>
                            <!-- Periodo -->
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info fw-semibold">{{ $paralelo->periodo->nombre }}</span>
                            </td>
                            <!-- Profesor que da la clase -->
                            <td>
                                <div class="fw-semibold text-dark">{{ $paralelo->profesor->nombre_completo }}</div>
                                <small class="text-muted">C.I.: {{ $paralelo->profesor->cedula }} | {{ $paralelo->profesor->correo }}</small>
                            </td>
                            <!-- Si necesita aula teorica o laboratorio -->
                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                    {{ ucfirst($paralelo->materia->tipo_aula) }}
                                </span>
                            </td>
                            <!-- Botones para editar o eliminar -->
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('paralelos.edit', $paralelo) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('paralelos.destroy', $paralelo) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este paralelo?');">
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
                        <!-- Mensaje si no hay registros -->
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-diagram-3 fs-1 d-block mb-2"></i>
                                No se encontraron paralelos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <!-- Paginacion de paralelos -->
    @if($paralelos->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $paralelos->links() }}
        </div>
    @endif
</div>
@endsection
