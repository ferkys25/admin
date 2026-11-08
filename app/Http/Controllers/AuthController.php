<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Controlador para manejar el inicio y cierre de sesion
class AuthController extends Controller
{
    // Muestra la vista de login o redirige si ya inicio sesion
    public function showLogin()
    {
        // Si el usuario ya esta logueado lo mandamos a su pantalla correspondiente segun su rol
        if (Auth::check()) {
            return Auth::user()->role === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('mi-horario');
        }

        return view('auth.login');
    }

    // Procesa el formulario de login
    public function login(Request $request)
    {
        // Validamos que no dejen los campos vacios
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'El correo electrónico es requerido.',
            'email.email' => 'Ingrese un formato de correo válido.',
            'password.required' => 'La contraseña es requerida.',
        ]);

        // Verificamos si el correo y la contrasena coinciden en la base
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Si es admin entra al panel completo, si es otro entra a ver su horario
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', "Bienvenido Administrador, {$user->name}.");
            }

            return redirect()->intended(route('mi-horario'))
                ->with('info', "Sesión iniciada como {$user->role}: {$user->name}.");
        }

        // Si los datos no coinciden mostramos error en pantalla
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    // Cierra la sesion del usuario actual
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Has cerrado sesión exitosamente.');
    }
}
