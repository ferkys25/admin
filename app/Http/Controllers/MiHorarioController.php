<?php

namespace App\Http\Controllers;

use App\Models\Paralelo;
use App\Models\Periodo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MiHorarioController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $periodoActual = Periodo::orderByDesc('fecha_inicio')->first();

        // Si es profesor, buscar paralelos asignados por correo o cedula si coincide
        $paralelos = collect();
        if ($user->role === 'profesor') {
            $paralelos = Paralelo::with(['materia', 'periodo', 'profesor'])
                ->whereHas('profesor', function ($query) use ($user) {
                    $query->where('correo', $user->email);
                })
                ->get();

            // Si aún no está vinculado directamente por email, mostrar los paralelos del período actual a modo de vista de solo lectura
            if ($paralelos->isEmpty() && $periodoActual) {
                $paralelos = Paralelo::with(['materia', 'periodo', 'profesor'])
                    ->where('periodo_id', $periodoActual->id)
                    ->take(5)
                    ->get();
            }
        } else {
            // Estudiante: vista informativa del período actual
            if ($periodoActual) {
                $paralelos = Paralelo::with(['materia', 'periodo', 'profesor'])
                    ->where('periodo_id', $periodoActual->id)
                    ->take(5)
                    ->get();
            }
        }

        return view('horarios.mi-horario', compact('user', 'periodoActual', 'paralelos'));
    }
}
