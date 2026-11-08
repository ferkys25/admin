@extends('layouts.app')

@section('title', 'Crear Materia')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <!-- Encabezado con boton para volver a la lista -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">
                    <i class="bi bi-plus-circle text-danger me-2"></i>Registrar Nueva Materia
                </h3>
                <p class="text-muted mb-0">Definición de asignaturas y requerimientos de infraestructura.</p>
            </div>
            <a href="{{ route('materias.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver a la Lista
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <!-- Formulario para guardar la nueva materia -->
                <form action="{{ route('materias.store') }}" method="POST">
                    @csrf

                    <!-- Codigo de la materia -->
                    <div class="mb-3">
                        <label for="codigo" class="form-label fw-bold text-dark">
                            Código de la Materia <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="codigo" 
                               id="codigo" 
                               class="form-control @error('codigo') is-invalid @enderror" 
                               value="{{ old('codigo') }}" 
                               placeholder="Ej: SIS-301, MAT-102" 
                               required>
                        @error('codigo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nombre de la materia -->
                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold text-dark">
                            Nombre de la Asignatura <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="nombre" 
                               id="nombre" 
                               class="form-control @error('nombre') is-invalid @enderror" 
                               value="{{ old('nombre') }}" 
                               placeholder="Ej: Estructuras de Datos y Algoritmos" 
                               required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <!-- Horas semanales que se dictan -->
                        <div class="col-md-6">
                            <label for="horas_semanales" class="form-label fw-bold text-dark">
                                Horas Semanales <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   name="horas_semanales" 
                                   id="horas_semanales" 
                                   class="form-control @error('horas_semanales') is-invalid @enderror" 
                                   value="{{ old('horas_semanales', 4) }}" 
                                   min="1" 
                                   max="40" 
                                   required>
                            @error('horas_semanales')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tipo de aula que necesita la materia -->
                        <div class="col-md-6">
                            <label for="tipo_aula" class="form-label fw-bold text-dark">
                                Tipo de Aula Requerida <span class="text-danger">*</span>
                            </label>
                            <select name="tipo_aula" id="tipo_aula" class="form-select @error('tipo_aula') is-invalid @enderror" required>
                                <option value="teorica" {{ old('tipo_aula') === 'teorica' ? 'selected' : '' }}>Aula Teórica (Pizarrón / Proyector)</option>
                                <option value="laboratorio" {{ old('tipo_aula') === 'laboratorio' ? 'selected' : '' }}>Laboratorio (Equipos de Computación)</option>
                            </select>
                            @error('tipo_aula')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Botones para guardar o cancelar -->
                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('materias.index') }}" class="btn btn-light">Cancelar</a>
                        <button type="submit" class="btn btn-udla px-4">
                            <i class="bi bi-save me-1"></i> Guardar Materia
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
