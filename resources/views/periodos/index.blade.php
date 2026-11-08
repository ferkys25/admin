@extends('layouts.app')

@section('title', 'Períodos Académicos')

@section('content')
<!-- Encabezado con boton para agregar un nuevo periodo -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">
            <i class="bi bi-calendar3 text-danger me-2"></i>Períodos Académicos
        </h3>
        <p class="text-muted mb-0">Gestión de semestres y ciclos lectivos para la generación de horarios.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('periodos.create') }}" class="btn btn-udla shadow-sm">
            <i class="bi bi-calendar-plus me-1"></i> Nuevo Período
        </a>
    </div>
</div>

<!-- Tabla que lista los periodos o semestres creados -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4">Nombre del Período</th>
                        <th>Fecha de Inicio</th>
                        <th>Fecha de Fin</th>
                        <th>Paralelos Asignados</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Recorremos los periodos academicos -->
                    @forelse($periodos as $periodo)
                        <tr>
                            <!-- Nombre del ciclo lectivo -->
                            <td class="ps-4 fw-bold text-dark">
                                <i class="bi bi-calendar-check me-2 text-primary"></i>{{ $periodo->nombre }}
                            </td>
                            <!-- Fechas de inicio y fin formateadas -->
                            <td>{{ $periodo->fecha_inicio ? $periodo->fecha_inicio->format('d/m/Y') : '-' }}</td>
                            <td>{{ $periodo->fecha_fin ? $periodo->fecha_fin->format('d/m/Y') : '-' }}</td>
                            <!-- Cantidad de paralelos creados en este periodo -->
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $periodo->paralelos_count }} paralelo(s)
                                </span>
                            </td>
                            <!-- Botones para editar o eliminar -->
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('periodos.edit', $periodo) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('periodos.destroy', $periodo) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar el período {{ $periodo->nombre }}?');">
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
                        <!-- Mensaje si no hay periodos creados -->
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                                No hay períodos académicos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <!-- Paginacion de periodos -->
    @if($periodos->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $periodos->links() }}
        </div>
    @endif
</div>
@endsection
