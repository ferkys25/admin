<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Models\Paralelo;
use App\Models\Periodo;
use App\Models\Profesor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

// Controlador para administrar los paralelos o asignaciones
class ParaleloController extends Controller
{
    // Muestra la lista de paralelos creados con opcion de filtrar por periodo
    public function index(Request $request)
    {
        $periodoId = $request->query('periodo_id');

        $paralelos = Paralelo::with(['materia', 'periodo', 'profesor'])
            ->when($periodoId, function ($query, $periodoId) {
                $query->where('periodo_id', $periodoId);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $periodos = Periodo::orderByDesc('fecha_inicio')->get();

        return view('paralelos.index', compact('paralelos', 'periodos', 'periodoId'));
    }

    // Muestra el formulario para crear un nuevo paralelo
    public function create()
    {
        // Cargamos los periodos y materias para llenar los primeros selects
        $periodos = Periodo::orderByDesc('fecha_inicio')->get();
        $materias = Materia::orderBy('nombre')->get();

        return view('paralelos.create', compact('periodos', 'materias'));
    }

    // Esta funcion responde a la peticion fetch de javascript
    // Devuelve en formato json unicamente los profesores que pueden dar la materia seleccionada
    public function getProfesoresPorMateria(Materia $materia): JsonResponse
    {
        // Buscamos en la relacion pivote los profesores asignados a esta materia
        $profesores = $materia->profesores()
            ->select('profesores.id', 'profesores.cedula', 'profesores.nombre', 'profesores.apellido', 'profesores.correo')
            ->orderBy('apellido')
            ->get();

        return response()->json([
            'success' => true,
            'materia' => [
                'id' => $materia->id,
                'codigo' => $materia->codigo,
                'nombre' => $materia->nombre,
            ],
            'profesores' => $profesores,
        ]);
    }

    // Guarda el paralelo en la base de datos
    public function store(Request $request)
    {
        $materiaId = $request->input('materia_id');

        // Validamos que los datos existan
        $validated = $request->validate([
            'periodo_id' => ['required', 'exists:periodos,id'],
            'materia_id' => ['required', 'exists:materias,id'],
            // Aqui revisamos en el servidor que el profesor realmente este asignado a esta materia
            'profesor_id' => [
                'required',
                'exists:profesores,id',
                Rule::exists('profesor_materia', 'profesor_id')->where(function ($query) use ($materiaId) {
                    $query->where('materia_id', $materiaId);
                }),
            ],
            'numero' => ['required', 'string', 'max:50'],
        ], [
            'periodo_id.required' => 'Debe seleccionar un período académico.',
            'periodo_id.exists' => 'El período académico seleccionado no es válido.',
            'materia_id.required' => 'Debe seleccionar una materia.',
            'materia_id.exists' => 'La materia seleccionada no es válida.',
            'profesor_id.required' => 'Debe seleccionar un profesor.',
            'profesor_id.exists' => 'El profesor seleccionado no está habilitado para impartir esta materia.',
            'numero.required' => 'Debe especificar el identificador o número del paralelo.',
        ]);

        // Creamos el registro del paralelo
        $paralelo = Paralelo::create($validated);

        return redirect()->route('paralelos.index')
            ->with('success', "Paralelo {$paralelo->numero} de {$paralelo->materia->nombre} registrado correctamente.");
    }

    // Muestra el formulario para editar un paralelo existente
    public function edit(Paralelo $paralelo)
    {
        $periodos = Periodo::orderByDesc('fecha_inicio')->get();
        $materias = Materia::orderBy('nombre')->get();
        $profesoresHabilitados = $paralelo->materia->profesores()->orderBy('apellido')->get();

        return view('paralelos.edit', compact('paralelo', 'periodos', 'materias', 'profesoresHabilitados'));
    }

    // Actualiza los datos de un paralelo
    public function update(Request $request, Paralelo $paralelo)
    {
        $materiaId = $request->input('materia_id');

        $validated = $request->validate([
            'periodo_id' => ['required', 'exists:periodos,id'],
            'materia_id' => ['required', 'exists:materias,id'],
            'profesor_id' => [
                'required',
                'exists:profesores,id',
                Rule::exists('profesor_materia', 'profesor_id')->where(function ($query) use ($materiaId) {
                    $query->where('materia_id', $materiaId);
                }),
            ],
            'numero' => ['required', 'string', 'max:50'],
        ], [
            'periodo_id.required' => 'Debe seleccionar un período académico.',
            'materia_id.required' => 'Debe seleccionar una materia.',
            'profesor_id.required' => 'Debe seleccionar un profesor.',
            'profesor_id.exists' => 'El profesor seleccionado no está habilitado para impartir esta materia.',
            'numero.required' => 'Debe especificar el identificador del paralelo.',
        ]);

        $paralelo->update($validated);

        return redirect()->route('paralelos.index')
            ->with('success', "Paralelo {$paralelo->numero} actualizado exitosamente.");
    }

    // Elimina un paralelo
    public function destroy(Paralelo $paralelo)
    {
        $paralelo->delete();

        return redirect()->route('paralelos.index')
            ->with('success', "Paralelo eliminado exitosamente.");
    }
}
