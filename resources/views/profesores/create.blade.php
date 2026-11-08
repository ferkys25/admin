@extends('layouts.app')

@section('title', 'Registrar Profesor')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-dark mb-0">
                <i class="bi bi-person-plus text-danger me-2"></i>Registrar Nuevo Profesor
            </h4>
            <a href="{{ route('profesores.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Volver a la Lista
            </a>
        </div>

        <!-- Banner para probar la validación Back-End Módulo 10 -->
        <div class="alert alert-warning border-0 shadow-sm mb-3">
            <div class="fw-bold mb-1"><i class="bi bi-shield-lock-fill me-1"></i> Validación de Cédula Ecuatoriana (Módulo 10 en Servidor):</div>
            <div class="small">
                El sistema valida matemáticamente el dígito verificador en el Back-End. Haz clic para probar:
                <br>
                <button type="button" class="btn btn-sm btn-success mt-2 me-1" onclick="document.getElementById('cedula').value = '1710034065'">
                    Cédula Válida: 1710034065
                </button>
                <button type="button" class="btn btn-sm btn-danger mt-2" onclick="document.getElementById('cedula').value = '1710034060'">
                    Cédula Falsa: 1710034060
                </button>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('profesores.store') }}" method="POST">
                    @csrf

                    <!-- Cédula de Identidad -->
                    <div class="mb-3">
                        <label for="cedula" class="form-label fw-bold">Cédula de Identidad (Dato Sensible) *</label>
                        <input type="text" name="cedula" id="cedula" class="form-control @error('cedula') is-invalid @enderror" value="{{ old('cedula') }}" placeholder="Ej: 1710034065" maxlength="10" required>
                        @error('cedula')
                            <div class="invalid-feedback d-block fw-bold">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Correo Electrónico -->
                    <div class="mb-3">
                        <label for="correo" class="form-label fw-bold">Correo Institucional *</label>
                        <input type="email" name="correo" id="correo" class="form-control @error('correo') is-invalid @enderror" value="{{ old('correo') }}" placeholder="docente@udla.edu.ec" required>
                        @error('correo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <!-- Nombres -->
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label fw-bold">Nombres *</label>
                            <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" placeholder="Ej: Carlos Andrés" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Apellidos -->
                        <div class="col-md-6 mb-3">
                            <label for="apellido" class="form-label fw-bold">Apellidos *</label>
                            <input type="text" name="apellido" id="apellido" class="form-control @error('apellido') is-invalid @enderror" value="{{ old('apellido') }}" placeholder="Ej: Mendoza Morales" required>
                            @error('apellido')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Materias Habilitadas (profesor_materia) -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Materias que puede impartir (Tabla profesor_materia):</label>
                        <div class="border rounded p-3 bg-light" style="max-height: 200px; overflow-y: auto;">
                            @forelse($materias as $materia)
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" name="materias[]" value="{{ $materia->id }}" id="m_{{ $materia->id }}" {{ in_array($materia->id, old('materias', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="m_{{ $materia->id }}">
                                        <strong>{{ $materia->codigo }}</strong> - {{ $materia->nombre }}
                                    </label>
                                </div>
                            @empty
                                <div class="text-muted small">No hay materias disponibles aún.</div>
                            @endforelse
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('profesores.index') }}" class="btn btn-light">Cancelar</a>
                        <button type="submit" class="btn btn-udla px-4">Guardar Profesor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
