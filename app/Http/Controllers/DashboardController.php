<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Models\Paralelo;
use App\Models\Periodo;
use App\Models\Profesor;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_profesores' => Profesor::count(),
            'total_materias' => Materia::count(),
            'total_periodos' => Periodo::count(),
            'total_paralelos' => Paralelo::count(),
        ];

        $ultimosParalelos = Paralelo::with(['materia', 'periodo', 'profesor'])
            ->latest()
            ->take(6)
            ->get();

        $materiasSinProfesor = Materia::doesntHave('profesores')->count();

        return view('admin.dashboard', compact('stats', 'ultimosParalelos', 'materiasSinProfesor'));
    }
}
