<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MateriaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $materias = Materia::withCount(['profesores', 'paralelos'])
            ->when($search, function ($query, $search) {
                $query->where('nombre', 'like', "%{$search}%")
                      ->orWhere('codigo', 'like', "%{$search}%");
            })
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view('materias.index', compact('materias', 'search'));
    }

    public function create()
    {
        return view('materias.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => ['required', 'string', 'max:50', 'unique:materias,codigo'],
            'nombre' => ['required', 'string', 'max:150'],
            'horas_semanales' => ['required', 'integer', 'min:1', 'max:40'],
            'tipo_aula' => ['required', 'in:teorica,laboratorio'],
        ], [
            'codigo.required' => 'El código de la materia es obligatorio.',
            'codigo.unique' => 'Este código ya está asignado a otra materia.',
            'nombre.required' => 'El nombre de la materia es obligatorio.',
            'horas_semanales.required' => 'Las horas semanales son requeridas.',
            'tipo_aula.required' => 'Debe seleccionar el tipo de aula.',
        ]);

        $materia = Materia::create($validated);

        return redirect()->route('materias.index')
            ->with('success', "Materia {$materia->nombre} creada exitosamente.");
    }

    public function edit(Materia $materia)
    {
        return view('materias.edit', compact('materia'));
    }

    public function update(Request $request, Materia $materia)
    {
        $validated = $request->validate([
            'codigo' => ['required', 'string', 'max:50', Rule::unique('materias', 'codigo')->ignore($materia->id)],
            'nombre' => ['required', 'string', 'max:150'],
            'horas_semanales' => ['required', 'integer', 'min:1', 'max:40'],
            'tipo_aula' => ['required', 'in:teorica,laboratorio'],
        ], [
            'codigo.required' => 'El código de la materia es obligatorio.',
            'codigo.unique' => 'Este código ya está asignado a otra materia.',
            'nombre.required' => 'El nombre de la materia es obligatorio.',
            'horas_semanales.required' => 'Las horas semanales son requeridas.',
            'tipo_aula.required' => 'Debe seleccionar el tipo de aula.',
        ]);

        $materia->update($validated);

        return redirect()->route('materias.index')
            ->with('success', "Materia {$materia->nombre} actualizada correctamente.");
    }

    public function destroy(Materia $materia)
    {
        $nombre = $materia->nombre;
        $materia->delete();

        return redirect()->route('materias.index')
            ->with('success', "Materia {$nombre} eliminada correctamente.");
    }
}
