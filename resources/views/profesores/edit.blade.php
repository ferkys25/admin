@extends('layouts.app')

@section('title', 'Editar Profesor')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Encabezado con el nombre del profesor que estamos editando -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">
                    <i class="bi bi-pencil-square text-danger me-2"></i>Editar Profesor: {{ $profesor->nombre_completo }}
                </h3>
                <p class="text-muted mb-0">Actualización de datos y materias que puede dictar.</p>
            </div>
            <a href="{{ route('profesores.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver a la Lista
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <!-- Formulario para actualizar usando metodo PUT -->
                <form action="{{ route('profesores.update', $profesor) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-3">
                        <!-- Campo para la cedula del profesor -->
                        <div class="col-md-6">
                            <label for="cedula" class="form-label fw-bold text-dark">
                                Cédula de Identidad <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="cedula" 
                                   id="cedula" 
                                   class="form-control @error('cedula') is-invalid @enderror" 
                                   value="{{ old('cedula', $profesor->cedula) }}" 
                                   maxlength="10" 
                                   required>
                            @error('cedula')
                                <div class="invalid-feedback d-block fw-semibold text-danger mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Campo para el correo -->
                        <div class="col-md-6">
                            <label for="correo" class="form-label fw-bold text-dark">
                                Correo Institucional <span class="text-danger">*</span>
                            </label>
                            <input type="email" 
                                   name="correo" 
                                   id="correo" 
                                   class="form-control @error('correo') is-invalid @enderror" 
                                   value="{{ old('correo', $profesor->correo) }}" 
                                   required>
                            @error('correo')
                                <div class="invalid-feedback d-block fw-semibold text-danger mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <!-- Campo para los nombres -->
                        <div class="col-md-6">
                            <label for="nombre" class="form-label fw-bold text-dark">
                                Nombres <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="nombre" 
                                   id="nombre" 
                                   class="form-control @error('nombre') is-invalid @enderror" 
                                   value="{{ old('nombre', $profesor->nombre) }}" 
                                   required>
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Campo para los apellidos -->
                        <div class="col-md-6">
                            <label for="apellido" class="form-label fw-bold text-dark">
                                Apellidos <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="apellido" 
                                   id="apellido" 
                                   class="form-control @error('apellido') is-invalid @enderror" 
                                   value="{{ old('apellido', $profesor->apellido) }}" 
                                   required>
                            @error('apellido')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Lista de checkboxes con las materias que puede impartir -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark d-block">
                            Materias Habilitadas:
                            <small class="text-muted fw-normal d-block">Marca las materias que este docente esta capacitado para dictar.</small>
                        </label>
                        <div class="border rounded p-3 bg-light" style="max-height: 220px; overflow-y: auto;">
                            <div class="row g-2">
                                @forelse($materias as $materia)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <!-- Si el profesor ya tenia la materia asignada, se marca automaticamente -->
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   name="materias[]" 
                                                   value="{{ $materia->id }}" 
                                                   id="materia_{{ $materia->id }}"
                                                   {{ in_array($materia->id, old('materias', $materiasAsignadas)) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="materia_{{ $materia->id }}">
                                                <strong>{{ $materia->codigo }}</strong> - {{ $materia->nombre }}
                                            </label>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-muted small">No hay materias disponibles.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Botones para guardar o cancelar -->
                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('profesores.index') }}" class="btn btn-light">Cancelar</a>
                        <button type="submit" class="btn btn-udla px-4">
                            <i class="bi bi-save me-1"></i> Actualizar Profesor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
