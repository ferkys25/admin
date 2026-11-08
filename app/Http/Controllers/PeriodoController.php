<?php

namespace App\Http\Controllers;

use App\Models\Periodo;
use Illuminate\Http\Request;

class PeriodoController extends Controller
{
    public function index(Request $request)
    {
        $periodos = Periodo::withCount('paralelos')
            ->orderByDesc('fecha_inicio')
            ->paginate(10);

        return view('periodos.index', compact('periodos'));
    }

    public function create()
    {
        return view('periodos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
        ], [
            'nombre.required' => 'El nombre del período es requerido.',
            'fecha_inicio.required' => 'La fecha de inicio es requerida.',
            'fecha_fin.required' => 'La fecha de fin es requerida.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la de inicio.',
        ]);

        $periodo = Periodo::create($validated);

        return redirect()->route('periodos.index')
            ->with('success', "Período {$periodo->nombre} creado correctamente.");
    }

    public function edit(Periodo $periodo)
    {
        return view('periodos.edit', compact('periodo'));
    }

    public function update(Request $request, Periodo $periodo)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
        ], [
            'nombre.required' => 'El nombre del período es requerido.',
            'fecha_inicio.required' => 'La fecha de inicio es requerida.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la de inicio.',
        ]);

        $periodo->update($validated);

        return redirect()->route('periodos.index')
            ->with('success', "Período {$periodo->nombre} actualizado correctamente.");
    }

    public function destroy(Periodo $periodo)
    {
        $nombre = $periodo->nombre;
        $periodo->delete();

        return redirect()->route('periodos.index')
            ->with('success', "Período {$nombre} eliminado.");
    }
}
