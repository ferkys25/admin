<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfesorRequest;
use App\Http\Requests\UpdateProfesorRequest;
use App\Models\Materia;
use App\Models\Profesor;
use Illuminate\Http\Request;

// Controlador para gestionar los profesores y las materias que dictan
class ProfesorController extends Controller
{
    // Muestra la lista de profesores y permite buscar por nombre, apellido, cedula o correo
    public function index(Request $request)
    {
        $search = $request->query('search');

        $profesores = Profesor::with('materias')
            ->when($search, function ($query, $search) {
                $query->where('nombre', 'like', "%{$search}%")
                      ->orWhere('apellido', 'like', "%{$search}%")
                      ->orWhere('cedula', 'like', "%{$search}%")
                      ->orWhere('correo', 'like', "%{$search}%");
            })
            ->orderBy('apellido')
            ->paginate(10)
            ->withQueryString();

        return view('profesores.index', compact('profesores', 'search'));
    }

    // Muestra el formulario para registrar un nuevo docente
    public function create()
    {
        // Traemos las materias para que el usuario pueda marcar las que el profesor puede dar
        $materias = Materia::orderBy('nombre')->get();
        return view('profesores.create', compact('materias'));
    }

    // Guarda el profesor validando la cedula con StoreProfesorRequest
    public function store(StoreProfesorRequest $request)
    {
        // Guarda los datos basicos del profesor
        $profesor = Profesor::create($request->validated());

        // Si marco materias, las guarda en la tabla pivote profesor_materia
        if ($request->has('materias')) {
            $profesor->materias()->sync($request->input('materias', []));
        }

        return redirect()->route('profesores.index')
            ->with('success', "Profesor {$profesor->nombre_completo} registrado exitosamente.");
    }

    // Muestra el formulario de edicion de un profesor
    public function edit(Profesor $profesore)
    {
        $profesor = $profesore;
        $materias = Materia::orderBy('nombre')->get();
        // Obtenemos los ids de las materias que ya tiene asignadas
        $materiasAsignadas = $profesor->materias->pluck('id')->toArray();

        return view('profesores.edit', compact('profesor', 'materias', 'materiasAsignadas'));
    }

    // Actualiza los datos del profesor
    public function update(UpdateProfesorRequest $request, Profesor $profesore)
    {
        $profesor = $profesore;
        $profesor->update($request->validated());

        // Actualizamos las materias asociadas en la tabla pivote
        $profesor->materias()->sync($request->input('materias', []));

        return redirect()->route('profesores.index')
            ->with('success', "Datos del profesor {$profesor->nombre_completo} actualizados correctamente.");
    }

    // Elimina un profesor del sistema
    public function destroy(Profesor $profesore)
    {
        $profesor = $profesore;
        $nombre = $profesor->nombre_completo;
        $profesor->delete();

        return redirect()->route('profesores.index')
            ->with('success', "Profesor {$nombre} eliminado del sistema.");
    }
}
