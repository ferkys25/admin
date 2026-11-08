@extends('layouts.app')

@section('title', 'Editar Paralelo')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Encabezado con el numero del paralelo que estamos editando -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">
                    <i class="bi bi-pencil-square text-danger me-2"></i>Editar Paralelo: {{ $paralelo->numero }}
                </h3>
                <p class="text-muted mb-0">Materia: {{ $paralelo->materia->nombre }} ({{ $paralelo->periodo->nombre }})</p>
            </div>
            <a href="{{ route('paralelos.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver a la Lista
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <!-- Formulario de actualizacion del paralelo -->
                <form action="{{ route('paralelos.update', $paralelo) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Dropdown 1: Seleccion de periodo -->
                    <div class="mb-3">
                        <label for="periodo_id" class="form-label fw-bold text-dark">
                            Período Académico <span class="text-danger">*</span>
                        </label>
                        <select name="periodo_id" id="periodo_id" class="form-select @error('periodo_id') is-invalid @enderror" required>
                            @foreach($periodos as $periodo)
                                <option value="{{ $periodo->id }}" {{ old('periodo_id', $paralelo->periodo_id) == $periodo->id ? 'selected' : '' }}>
                                    {{ $periodo->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('periodo_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Dropdown 2: Seleccion de materia -->
                    <div class="mb-3">
                        <label for="materia_id" class="form-label fw-bold text-dark">
                            Materia <span class="text-danger">*</span>
                        </label>
                        <select name="materia_id" id="materia_id" class="form-select @error('materia_id') is-invalid @enderror" required>
                            @foreach($materias as $materia)
                                <option value="{{ $materia->id }}" {{ old('materia_id', $paralelo->materia_id) == $materia->id ? 'selected' : '' }}>
                                    [{{ $materia->codigo }}] {{ $materia->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('materia_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Dropdown 3: Seleccion de profesor habilitado -->
                    <div class="mb-3">
                        <label for="profesor_id" class="form-label fw-bold text-dark">
                            Profesor Habilitado <span class="text-danger">*</span>
                        </label>
                        <select name="profesor_id" id="profesor_id" class="form-select @error('profesor_id') is-invalid @enderror" required>
                            @foreach($profesoresHabilitados as $prof)
                                <option value="{{ $prof->id }}" {{ old('profesor_id', $paralelo->profesor_id) == $prof->id ? 'selected' : '' }}>
                                    {{ $prof->apellido }} {{ $prof->nombre }} (C.I. {{ $prof->cedula }})
                                </option>
                            @endforeach
                        </select>
                        @error('profesor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Numero o identificador del paralelo -->
                    <div class="mb-4">
                        <label for="numero" class="form-label fw-bold text-dark">
                            Número / Código del Paralelo <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="numero" 
                               id="numero" 
                               class="form-control @error('numero') is-invalid @enderror" 
                               value="{{ old('numero', $paralelo->numero) }}" 
                               required>
                        @error('numero')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Botones de accion -->
                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('paralelos.index') }}" class="btn btn-light">Cancelar</a>
                        <button type="submit" class="btn btn-udla px-4">
                            <i class="bi bi-save me-1"></i> Actualizar Paralelo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Si cambian la materia en la edicion, actualizamos los profesores con fetch
document.getElementById('materia_id').addEventListener('change', function () {
    var materiaId = this.value;
    var selectProfesor = document.getElementById('profesor_id');

    selectProfesor.innerHTML = '<option value="">Cargando profesores...</option>';
    selectProfesor.disabled = true;

    fetch('/admin/materias/' + materiaId + '/profesores')
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            selectProfesor.innerHTML = '';
            if (data.profesores && data.profesores.length > 0) {
                data.profesores.forEach(function (p) {
                    var opt = document.createElement('option');
                    opt.value = p.id;
                    opt.textContent = p.apellido + ' ' + p.nombre + ' (' + p.cedula + ')';
                    selectProfesor.appendChild(opt);
                });
                selectProfesor.disabled = false;
            } else {
                selectProfesor.innerHTML = '<option value="">-- Sin profesores habilitados --</option>';
            }
        })
        .catch(function (e) {
            console.error('Error al cargar:', e);
            selectProfesor.innerHTML = '<option value="">Error al cargar profesores</option>';
        });
});
</script>
@endpush
