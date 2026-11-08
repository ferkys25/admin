@extends('layouts.app')

@section('title', 'Asignar Paralelo')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-dark mb-0">
                <i class="bi bi-diagram-3 text-danger me-2"></i>Nueva Asignación de Paralelo
            </h4>
            <a href="{{ route('paralelos.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Volver a la Lista
            </a>
        </div>

        <!-- Explicación amigable -->
        <div class="alert alert-info border-0 shadow-sm mb-3">
            <i class="bi bi-info-circle-fill me-1"></i>
            <strong>Dropdowns Dependientes (Fetch):</strong> Al seleccionar una Materia, el campo de Profesor se llenará automáticamente solo con los docentes habilitados para esa materia en la base de datos.
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('paralelos.store') }}" method="POST">
                    @csrf

                    <!-- 1. Período -->
                    <div class="mb-3">
                        <label for="periodo_id" class="form-label fw-bold">1. Período Académico *</label>
                        <select name="periodo_id" id="periodo_id" class="form-select @error('periodo_id') is-invalid @enderror" required>
                            <option value="">-- Seleccione un Período --</option>
                            @foreach($periodos as $periodo)
                                <option value="{{ $periodo->id }}" {{ old('periodo_id') == $periodo->id ? 'selected' : '' }}>
                                    {{ $periodo->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('periodo_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- 2. Materia -->
                    <div class="mb-3">
                        <label for="materia_id" class="form-label fw-bold">2. Materia *</label>
                        <select name="materia_id" id="materia_id" class="form-select @error('materia_id') is-invalid @enderror" required>
                            <option value="">-- Seleccione una Materia --</option>
                            @foreach($materias as $materia)
                                <option value="{{ $materia->id }}" {{ old('materia_id') == $materia->id ? 'selected' : '' }}>
                                    [{{ $materia->codigo }}] {{ $materia->nombre }} ({{ ucfirst($materia->tipo_aula) }})
                                </option>
                            @endforeach
                        </select>
                        @error('materia_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- 3. Profesor (Se actualiza con JavaScript) -->
                    <div class="mb-3">
                        <label for="profesor_id" class="form-label fw-bold">3. Profesor Habilitado *</label>
                        <select name="profesor_id" id="profesor_id" class="form-select @error('profesor_id') is-invalid @enderror" required disabled>
                            <option value="">-- Primero elija una materia arriba --</option>
                        </select>
                        <div id="profesor-mensaje" class="form-text text-muted">
                            Elija una materia para consultar los profesores habilitados.
                        </div>
                        @error('profesor_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Número de Paralelo -->
                    <div class="mb-4">
                        <label for="numero" class="form-label fw-bold">Número o Código de Paralelo *</label>
                        <input type="text" name="numero" id="numero" class="form-control @error('numero') is-invalid @enderror" value="{{ old('numero', '1') }}" placeholder="Ej: 1, 2 o GR-1" required>
                        @error('numero')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('paralelos.index') }}" class="btn btn-light">Cancelar</a>
                        <button type="submit" class="btn btn-udla px-4">Guardar Paralelo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Cuando el usuario cambia la materia seleccionada
document.getElementById('materia_id').addEventListener('change', function () {
    var materiaId = this.value;
    var selectProfesor = document.getElementById('profesor_id');
    var mensaje = document.getElementById('profesor-mensaje');

    // Si no ha seleccionado ninguna materia dejamos el select bloqueado
    if (!materiaId) {
        selectProfesor.innerHTML = '<option value="">-- Primero elija una materia arriba --</option>';
        selectProfesor.disabled = true;
        mensaje.textContent = 'Elija una materia para consultar los profesores habilitados.';
        return;
    }

    // Mostramos un texto mientras consulta
    selectProfesor.innerHTML = '<option value="">Cargando profesores...</option>';
    selectProfesor.disabled = true;
    mensaje.textContent = 'Buscando profesores en la base de datos...';

    // Pedimos al servidor la lista de profesores de esa materia
    fetch('/admin/materias/' + materiaId + '/profesores')
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            var profesores = data.profesores;

            // Vaciamos el select para llenarlo de nuevo
            selectProfesor.innerHTML = '';

            // Si esa materia no tiene docentes en la base
            if (profesores.length === 0) {
                selectProfesor.innerHTML = '<option value="">-- No hay profesores para esta materia --</option>';
                selectProfesor.disabled = true;
                mensaje.textContent = 'Atención: No hay docentes asociados a esta materia en la tabla pivote.';
            } else {
                selectProfesor.innerHTML = '<option value="">-- Seleccione un profesor (' + profesores.length + ' disponibles) --</option>';
                
                // Recorremos los profesores y los anadimos como opciones
                profesores.forEach(function (prof) {
                    var option = document.createElement('option');
                    option.value = prof.id;
                    option.textContent = prof.apellido + ' ' + prof.nombre + ' (' + prof.cedula + ')';
                    selectProfesor.appendChild(option);
                });

                // Habilitamos el select para que el usuario pueda escoger
                selectProfesor.disabled = false;
                mensaje.textContent = 'Se encontraron ' + profesores.length + ' profesor(es) capacitados.';
            }
        })
        .catch(function (error) {
            console.error('Error:', error);
            selectProfesor.innerHTML = '<option value="">Error al cargar profesores</option>';
        });
});
</script>
@endpush
