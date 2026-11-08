@extends('layouts.app')

@section('title', 'Crear Período Académico')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <!-- Encabezado con boton para volver atras -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">
                    <i class="bi bi-calendar-plus text-danger me-2"></i>Nuevo Período Académico
                </h3>
                <p class="text-muted mb-0">Configuración de un ciclo académico de la UDLA.</p>
            </div>
            <a href="{{ route('periodos.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver a la Lista
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <!-- Formulario para crear un nuevo periodo -->
                <form action="{{ route('periodos.store') }}" method="POST">
                    @csrf

                    <!-- Nombre del periodo -->
                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold text-dark">
                            Nombre del Período <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="nombre" 
                               id="nombre" 
                               class="form-control @error('nombre') is-invalid @enderror" 
                               value="{{ old('nombre') }}" 
                               placeholder="Ej: 2026-1 (Semestre 2026 - Primavera)" 
                               required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <!-- Fecha en la que inicia el periodo -->
                        <div class="col-md-6">
                            <label for="fecha_inicio" class="form-label fw-bold text-dark">
                                Fecha de Inicio <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   name="fecha_inicio" 
                                   id="fecha_inicio" 
                                   class="form-control @error('fecha_inicio') is-invalid @enderror" 
                                   value="{{ old('fecha_inicio', date('Y-03-01')) }}" 
                                   required>
                            @error('fecha_inicio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Fecha en la que termina el periodo -->
                        <div class="col-md-6">
                            <label for="fecha_fin" class="form-label fw-bold text-dark">
                                Fecha de Fin <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   name="fecha_fin" 
                                   id="fecha_fin" 
                                   class="form-control @error('fecha_fin') is-invalid @enderror" 
                                   value="{{ old('fecha_fin', date('Y-07-31')) }}" 
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
                            <i class="bi bi-save me-1"></i> Guardar Período
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
