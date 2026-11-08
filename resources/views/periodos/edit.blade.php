@extends('layouts.app')

@section('title', 'Editar Período')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <!-- Encabezado con el nombre del periodo que editamos -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">
                    <i class="bi bi-pencil-square text-danger me-2"></i>Editar Período: {{ $periodo->nombre }}
                </h3>
                <p class="text-muted mb-0">Modificación de fechas del ciclo académico.</p>
            </div>
            <a href="{{ route('periodos.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver a la Lista
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <!-- Formulario de actualizacion con metodo PUT -->
                <form action="{{ route('periodos.update', $periodo) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nombre del periodo -->
                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold text-dark">
                            Nombre del Período <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="nombre" 
                               id="nombre" 
                               class="form-control @error('nombre') is-invalid @enderror" 
                               value="{{ old('nombre', $periodo->nombre) }}" 
                               required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <!-- Fecha de inicio -->
                        <div class="col-md-6">
                            <label for="fecha_inicio" class="form-label fw-bold text-dark">
                                Fecha de Inicio <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   name="fecha_inicio" 
                                   id="fecha_inicio" 
                                   class="form-control @error('fecha_inicio') is-invalid @enderror" 
                                   value="{{ old('fecha_inicio', $periodo->fecha_inicio ? $periodo->fecha_inicio->format('Y-m-d') : '') }}" 
                                   required>
                            @error('fecha_inicio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Fecha de fin -->
                        <div class="col-md-6">
                            <label for="fecha_fin" class="form-label fw-bold text-dark">
                                Fecha de Fin <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   name="fecha_fin" 
                                   id="fecha_fin" 
                                   class="form-control @error('fecha_fin') is-invalid @enderror" 
                                   value="{{ old('fecha_fin', $periodo->fecha_fin ? $periodo->fecha_fin->format('Y-m-d') : '') }}" 
                                   required>
                            @error('fecha_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Botones para guardar o cancelar -->
                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('periodos.index') }}" class="btn btn-light">Cancelar</a>
                        <button type="submit" class="btn btn-udla px-4">
                            <i class="bi bi-save me-1"></i> Actualizar Período
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
