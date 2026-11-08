@extends('layouts.app')

@section('title', 'Mi Horario')

@section('content')
<!-- Alerta que explica que el usuario esta en modo de solo lectura -->
<div class="alert alert-warning border-0 shadow-sm d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="alert-heading fw-bold mb-1">
            <i class="bi bi-shield-lock-fill me-1"></i> Modo Solo Lectura (Usuario: {{ $user->name }})
        </h5>
        <p class="mb-0 small">
            Tu rol es <strong>{{ strtoupper($user->role) }}</strong>. El sistema restringio el acceso a la administracion; solo tienes permisos para consultar horarios.
        </p>
    </div>
    <!-- Si quien entra es admin le mostramos boton para regresar a su panel -->
    @if($user->role === 'admin')
        <a href="{{ route('admin.dashboard') }}" class="btn btn-udla btn-sm">Volver al Panel Admin</a>
    @endif
</div>

<div class="row">
    <!-- Tarjeta izquierda con la lista de materias asignadas -->
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-book me-1 text-danger"></i> Carga Académica Registrada
            </div>
            <ul class="list-group list-group-flush">
                <!-- Mostramos las materias cursadas o dictadas -->
                @forelse($paralelos as $paralelo)
                    <li class="list-group-item">
                        <div class="fw-bold">{{ $paralelo->materia->nombre }}</div>
                        <div class="small text-muted">
                            Código: {{ $paralelo->materia->codigo }} | Paralelo {{ $paralelo->numero }}
                        </div>
                        <div class="small text-secondary">
                            Docente: {{ $paralelo->profesor->nombre_completo }}
                        </div>
                    </li>
                @empty
                    <!-- Si no tiene materias en este ciclo -->
                    <li class="list-group-item text-center text-muted py-3">
                        No hay materias asignadas en este ciclo.
                    </li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- Tarjeta derecha con la cuadricula de horario semanal -->
    <div class="col-md-8 mb-3">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                <span><i class="bi bi-calendar-week me-1 text-danger"></i> Cuadrícula Semanal (Vista de Clases)</span>
                <span class="badge bg-secondary">Previsualización</span>
            </div>
            <div class="card-body p-2">
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th>Horario</th>
                                <th>Lunes</th>
                                <th>Martes</th>
                                <th>Miércoles</th>
                                <th>Jueves</th>
                                <th>Viernes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Franja de la manana 07:00 a 09:00 -->
                            <tr>
                                <td class="fw-bold table-light">07:00 - 09:00</td>
                                <td class="bg-primary text-white fw-bold p-2">Estructuras de Datos<br><small>Aula T-101</small></td>
                                <td class="text-muted">Libre</td>
                                <td class="bg-primary text-white fw-bold p-2">Estructuras de Datos<br><small>Aula T-101</small></td>
                                <td class="text-muted">Libre</td>
                                <td class="text-muted">Libre</td>
                            </tr>
                            <!-- Franja de 09:00 a 11:00 -->
                            <tr>
                                <td class="fw-bold table-light">09:00 - 11:00</td>
                                <td class="text-muted">Libre</td>
                                <td class="bg-success text-white fw-bold p-2">Sistemas Operativos<br><small>Lab L-204</small></td>
                                <td class="text-muted">Libre</td>
                                <td class="bg-success text-white fw-bold p-2">Sistemas Operativos<br><small>Lab L-204</small></td>
                                <td class="text-muted">Libre</td>
                            </tr>
                            <!-- Franja de 11:00 a 13:00 -->
                            <tr>
                                <td class="fw-bold table-light">11:00 - 13:00</td>
                                <td class="bg-warning text-dark fw-bold p-2">Bases de Datos<br><small>Aula T-205</small></td>
                                <td class="text-muted">Libre</td>
                                <td class="bg-warning text-dark fw-bold p-2">Bases de Datos<br><small>Aula T-205</small></td>
                                <td class="text-muted">Libre</td>
                                <td class="text-muted">Libre</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Nota informativa -->
                <div class="text-muted small mt-2 px-2">
                    <i class="bi bi-info-circle me-1"></i> Esta es la cuadrícula resultante que los profesores y alumnos consultan en su perfil.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
