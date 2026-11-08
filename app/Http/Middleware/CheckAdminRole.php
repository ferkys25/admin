<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

// Este middleware revisa si el usuario tiene permiso para entrar al panel de administracion
class CheckAdminRole
{
    public function handle(Request $request, Closure $next): Response
    {
        // Si el usuario no ha iniciado sesion lo mandamos a la pantalla de login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder al sistema.');
        }

        // Si inicio sesion pero es profesor o estudiante, no lo dejamos pasar a las rutas admin
        // Lo enviamos a su pantalla de solo lectura que es mi horario
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('mi-horario')->with('warning', 'Acceso restringido: Solo los administradores pueden gestionar la base de datos académica.');
        }

        // Si es admin puede continuar normalmente
        return $next($request);
    }
}
