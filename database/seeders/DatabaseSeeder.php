<?php

namespace Database\Seeders;

use App\Models\Materia;
use App\Models\Paralelo;
use App\Models\Periodo;
use App\Models\Profesor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Usuarios para probar el sistema (admin, profesor y estudiante)
        $admin = User::firstOrCreate(
            ['email' => 'admin@udla.edu.ec'],
            [
                'name' => 'Ing. Roberto Morales (Admin)',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        $userProfesor = User::firstOrCreate(
            ['email' => 'profesor@udla.edu.ec'],
            [
                'name' => 'Dr. Carlos Mendoza',
                'password' => Hash::make('password123'),
                'role' => 'profesor',
            ]
        );

        $userEstudiante = User::firstOrCreate(
            ['email' => 'estudiante@udla.edu.ec'],
            [
                'name' => 'Ana Paula Vaca',
                'password' => Hash::make('password123'),
                'role' => 'estudiante',
            ]
        );

        // Periodos academicos de ejemplo
        $periodo1 = Periodo::firstOrCreate(
            ['nombre' => '2026-1 (Semestre Ordinario I)'],
            [
                'fecha_inicio' => '2026-03-02',
                'fecha_fin' => '2026-07-24',
            ]
        );

        $periodo2 = Periodo::firstOrCreate(
            ['nombre' => '2026-2 (Semestre Ordinario II)'],
            [
                'fecha_inicio' => '2026-08-17',
                'fecha_fin' => '2026-12-18',
            ]
        );

        // Materias de la carrera
        $materia1 = Materia::firstOrCreate(
            ['codigo' => 'SIS-301'],
            [
                'nombre' => 'Estructuras de Datos y Algoritmos',
                'horas_semanales' => 4,
                'tipo_aula' => 'teorica',
            ]
        );

        $materia2 = Materia::firstOrCreate(
            ['codigo' => 'SIS-302'],
            [
                'nombre' => 'Sistemas Operativos',
                'horas_semanales' => 4,
                'tipo_aula' => 'laboratorio',
            ]
        );

        $materia3 = Materia::firstOrCreate(
            ['codigo' => 'SIS-401'],
            [
                'nombre' => 'Bases de Datos Avanzadas',
                'horas_semanales' => 4,
                'tipo_aula' => 'laboratorio',
            ]
        );

        $materia4 = Materia::firstOrCreate(
            ['codigo' => 'SIS-402'],
            [
                'nombre' => 'Redes de Comunicación de Datos',
                'horas_semanales' => 4,
                'tipo_aula' => 'laboratorio',
            ]
        );

        $materia5 = Materia::firstOrCreate(
            ['codigo' => 'MAT-201'],
            [
                'nombre' => 'Cálculo Multivariable',
                'horas_semanales' => 4,
                'tipo_aula' => 'teorica',
            ]
        );

        $materia6 = Materia::firstOrCreate(
            ['codigo' => 'MAT-202'],
            [
                'nombre' => 'Probabilidad y Estadística',
                'horas_semanales' => 3,
                'tipo_aula' => 'teorica',
            ]
        );

        // Profesores con cedulas reales para pasar la validacion
        $prof1 = Profesor::firstOrCreate(
            ['cedula' => '1710034065'],
            [
                'nombre' => 'Carlos',
                'apellido' => 'Mendoza Morales',
                'correo' => 'profesor@udla.edu.ec',
            ]
        );

        $prof2 = Profesor::firstOrCreate(
            ['cedula' => '0926687856'],
            [
                'nombre' => 'Beatriz',
                'apellido' => 'Navas Peña',
                'correo' => 'bnavas@udla.edu.ec',
            ]
        );

        $prof3 = Profesor::firstOrCreate(
            ['cedula' => '1719284711'],
            [
                'nombre' => 'Diego',
                'apellido' => 'Ortega Celi',
                'correo' => 'dortega@udla.edu.ec',
            ]
        );

        $prof4 = Profesor::firstOrCreate(
            ['cedula' => '1722839402'],
            [
                'nombre' => 'Elena',
                'apellido' => 'Flores Romero',
                'correo' => 'eflores@udla.edu.ec',
            ]
        );

        // Asociamos que materias puede dar cada profesor en la tabla pivote
        $prof1->materias()->syncWithoutDetaching([$materia1->id, $materia2->id]);
        $prof2->materias()->syncWithoutDetaching([$materia3->id, $materia4->id]);
        $prof3->materias()->syncWithoutDetaching([$materia5->id, $materia6->id]);
        $prof4->materias()->syncWithoutDetaching([$materia1->id, $materia3->id]);

        // Paralelos de prueba iniciales
        Paralelo::firstOrCreate(
            [
                'materia_id' => $materia1->id,
                'periodo_id' => $periodo1->id,
                'profesor_id' => $prof1->id,
                'numero' => '1',
            ]
        );

        Paralelo::firstOrCreate(
            [
                'materia_id' => $materia1->id,
                'periodo_id' => $periodo1->id,
                'profesor_id' => $prof4->id,
                'numero' => '2',
            ]
        );

        Paralelo::firstOrCreate(
            [
                'materia_id' => $materia2->id,
                'periodo_id' => $periodo1->id,
                'profesor_id' => $prof1->id,
                'numero' => '1',
            ]
        );

        Paralelo::firstOrCreate(
            [
                'materia_id' => $materia3->id,
                'periodo_id' => $periodo1->id,
                'profesor_id' => $prof2->id,
                'numero' => '1',
            ]
        );
    }
}
