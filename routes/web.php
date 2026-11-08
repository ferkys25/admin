<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\MiHorarioController;
use App\Http\Controllers\ParaleloController;
use App\Http\Controllers\PeriodoController;
use App\Http\Controllers\ProfesorController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Si entran a la pagina principal revisamos si ya inicio sesion
Route::get('/', function () {
    if (Auth::check()) {
        // Si es admin va al panel de control, sino va a ver su horario
        return Auth::user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('mi-horario');
    }
    // Si no ha iniciado sesion lo mandamos al login
    return redirect()->route('login');
});

// Rutas para iniciar sesion (solo para usuarios no autenticados)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Ruta para cerrar sesion
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Pantalla de solo lectura donde estudiantes y profesores ven su horario
Route::middleware('auth')->group(function () {
    Route::get('/mi-horario', [MiHorarioController::class, 'index'])->name('mi-horario');
});

// Rutas del panel de administracion
// Estan protegidas para que solo entre el usuario con rol admin
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Rutas para gestionar profesores
    Route::resource('profesores', ProfesorController::class);

    // Rutas para gestionar materias
    Route::resource('materias', MateriaController::class);

    // Rutas para gestionar periodos academicos
    Route::resource('periodos', PeriodoController::class);

    // Esta ruta responde a la peticion fetch para traer profesores de una materia
    Route::get('materias/{materia}/profesores', [ParaleloController::class, 'getProfesoresPorMateria'])
        ->name('materias.profesores');

    // Rutas para crear y ver paralelos
    Route::resource('paralelos', ParaleloController::class);
});
