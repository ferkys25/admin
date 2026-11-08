<?php

namespace Tests\Feature;

use App\Models\Materia;
use App\Models\Periodo;
use App\Models\Profesor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicAdministrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    // Prueba que los invitados no puedan entrar a rutas admin
    public function test_guest_is_redirected_to_login_when_accessing_admin(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_student_and_teacher_are_redirected_to_mi_horario(): void
    {
        $estudiante = User::where('role', 'estudiante')->first();
        $profesor = User::where('role', 'profesor')->first();

        // Estudiante intentando acceder al dashboard admin
        $response = $this->actingAs($estudiante)->get('/admin/dashboard');
        $response->assertRedirect('/mi-horario');

        // Profesor intentando acceder al módulo de profesores
        $response = $this->actingAs($profesor)->get('/admin/profesores');
        $response->assertRedirect('/mi-horario');
    }

    public function test_admin_has_full_access_to_admin_routes(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/admin/profesores');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/admin/materias');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/admin/paralelos/create');
        $response->assertStatus(200);
    }

    // Prueba que la validacion de la cedula rechace cedulas falsas
    public function test_invalid_cedula_fails_server_side_validation(): void
    {
        $admin = User::where('role', 'admin')->first();

        // Cédula con dígito verificador incorrecto (1710034060 en lugar de 1710034065)
        $response = $this->actingAs($admin)->post('/admin/profesores', [
            'cedula' => '1710034060',
            'nombre' => 'Test',
            'apellido' => 'Invalido',
            'correo' => 'invalido@udla.edu.ec',
        ]);

        $response->assertSessionHasErrors(['cedula']);
        $this->assertDatabaseMissing('profesores', ['correo' => 'invalido@udla.edu.ec']);

        // Cédula con provincia inexistente (99)
        $response = $this->actingAs($admin)->post('/admin/profesores', [
            'cedula' => '9910034065',
            'nombre' => 'Test2',
            'apellido' => 'Invalido2',
            'correo' => 'invalido2@udla.edu.ec',
        ]);

        $response->assertSessionHasErrors(['cedula']);
    }

    public function test_valid_cedula_passes_and_is_stored(): void
    {
        $admin = User::where('role', 'admin')->first();

        // Cédula válida 1717283921
        $response = $this->actingAs($admin)->post('/admin/profesores', [
            'cedula' => '1717283921',
            'nombre' => 'Gabriel',
            'apellido' => 'Salazar',
            'correo' => 'gsalazar@udla.edu.ec',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/admin/profesores');
        $this->assertDatabaseHas('profesores', [
            'cedula' => '1717283921',
            'correo' => 'gsalazar@udla.edu.ec',
        ]);
    }

    public function test_duplicate_cedula_fails_unique_rule(): void
    {
        $admin = User::where('role', 'admin')->first();

        // Cédula ya existente en el seeder (1710034065)
        $response = $this->actingAs($admin)->post('/admin/profesores', [
            'cedula' => '1710034065',
            'nombre' => 'Duplicado',
            'apellido' => 'Test',
            'correo' => 'otro@udla.edu.ec',
        ]);

        $response->assertSessionHasErrors(['cedula']);
    }

    // Prueba que la ruta de profesores devuelva solo los habilitados para esa materia
    public function test_ajax_endpoint_returns_only_professors_assigned_in_profesor_materia(): void
    {
        $admin = User::where('role', 'admin')->first();
        $materia = Materia::where('codigo', 'SIS-301')->first();

        $response = $this->actingAs($admin)->getJson("/admin/materias/{$materia->id}/profesores");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'materia' => ['id', 'codigo', 'nombre'],
            'profesores' => [
                '*' => ['id', 'cedula', 'nombre', 'apellido', 'correo']
            ]
        ]);

        $data = $response->json();
        $profesoresIds = collect($data['profesores'])->pluck('id')->toArray();
        $expectedIds = $materia->profesores->pluck('id')->toArray();

        $this->assertEqualsCanonicalizing($expectedIds, $profesoresIds);
    }

    // Prueba que no se pueda asignar a un profesor si no esta en la tabla pivote de esa materia
    public function test_cannot_assign_unqualified_professor_to_paralelo(): void
    {
        $admin = User::where('role', 'admin')->first();
        $periodo = Periodo::first();
        $materiaCalculo = Materia::where('codigo', 'MAT-201')->first();
        // Profesor Mendoza solo enseña SIS-301 y SIS-302, no MAT-201
        $profesorMendoza = Profesor::where('correo', 'profesor@udla.edu.ec')->first();

        $response = $this->actingAs($admin)->post('/admin/paralelos', [
            'periodo_id' => $periodo->id,
            'materia_id' => $materiaCalculo->id,
            'profesor_id' => $profesorMendoza->id,
            'numero' => '1',
        ]);

        $response->assertSessionHasErrors(['profesor_id']);
    }
}
