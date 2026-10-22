<?php

namespace Tests\Feature;

use App\Models\Equipo;
use App\Models\Prestamo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrestamoTest extends TestCase
{
    use RefreshDatabase;

    private function crearEncargado(): User
    {
        return User::factory()->create([
            'rol' => User::ROL_ENCARGADO,
        ]);
    }

    private function crearEstudiante(): User
    {
        return User::factory()->create([
            'rol' => User::ROL_ESTUDIANTE,
        ]);
    }

    private function crearEquipoDisponible(): Equipo
    {
        return Equipo::create([
            'codigo' => 'EQ-001',
            'nombre' => 'Laptop de prueba',
            'categoria' => 'Laptop',
            'estado' => Equipo::DISPONIBLE,
        ]);
    }

    public function test_el_encargado_puede_ver_la_gestion_de_prestamos(): void
    {
        $encargado = $this->crearEncargado();

        $this->actingAs($encargado)
            ->get(route('prestamos.index'))
            ->assertOk()
            ->assertSee('Gestión de Préstamos');
    }

    public function test_el_encargado_puede_prestar_un_equipo(): void
    {
        $encargado = $this->crearEncargado();
        $estudiante = $this->crearEstudiante();
        $equipo = $this->crearEquipoDisponible();

        $this->actingAs($encargado)
            ->post(route('prestamos.prestar'), [
                'equipo_id' => $equipo->id,
                'user_id' => $estudiante->id,
            ])
            ->assertRedirect(route('prestamos.index'));

        $this->assertDatabaseHas('prestamos', [
            'equipo_id' => $equipo->id,
            'user_id' => $estudiante->id,
        ]);

        $this->assertDatabaseHas('equipos', [
            'id' => $equipo->id,
            'estado' => Equipo::PRESTADO,
        ]);
    }

    public function test_no_se_puede_prestar_un_equipo_que_ya_esta_prestado(): void
    {
        $encargado = $this->crearEncargado();
        $estudiante = $this->crearEstudiante();

        $equipo = Equipo::create([
            'codigo' => 'EQ-002',
            'nombre' => 'Proyector de prueba',
            'categoria' => 'Proyector',
            'estado' => Equipo::PRESTADO,
        ]);

        $this->actingAs($encargado)
            ->post(route('prestamos.prestar'), [
                'equipo_id' => $equipo->id,
                'user_id' => $estudiante->id,
            ])
            ->assertRedirect(route('prestamos.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseCount('prestamos', 0);
    }

    public function test_no_se_puede_prestar_a_un_usuario_que_no_sea_estudiante(): void
    {
        $encargado = $this->crearEncargado();
        $otroEncargado = $this->crearEncargado();
        $equipo = $this->crearEquipoDisponible();

        $this->actingAs($encargado)
            ->post(route('prestamos.prestar'), [
                'equipo_id' => $equipo->id,
                'user_id' => $otroEncargado->id,
            ])
            ->assertRedirect(route('prestamos.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseCount('prestamos', 0);

        $this->assertDatabaseHas('equipos', [
            'id' => $equipo->id,
            'estado' => Equipo::DISPONIBLE,
        ]);
    }

    public function test_el_encargado_puede_devolver_un_equipo(): void
    {
        $encargado = $this->crearEncargado();
        $estudiante = $this->crearEstudiante();

        $equipo = Equipo::create([
            'codigo' => 'EQ-003',
            'nombre' => 'Multímetro de prueba',
            'categoria' => 'Multímetro',
            'estado' => Equipo::PRESTADO,
        ]);

        $prestamo = Prestamo::create([
            'equipo_id' => $equipo->id,
            'user_id' => $estudiante->id,
            'fecha_prestamo' => now()->toDateString(),
            'fecha_devolucion' => null,
        ]);

        $this->actingAs($encargado)
            ->patch(route('prestamos.devolver', $prestamo))
            ->assertRedirect(route('prestamos.index'));

        $this->assertNotNull(
            $prestamo->fresh()->fecha_devolucion
        );

        $this->assertEquals(
            Equipo::DISPONIBLE,
            $equipo->fresh()->estado
        );
    }

    public function test_no_se_puede_devolver_dos_veces_el_mismo_prestamo(): void
    {
        $encargado = $this->crearEncargado();
        $estudiante = $this->crearEstudiante();
        $equipo = $this->crearEquipoDisponible();

        $prestamo = Prestamo::create([
            'equipo_id' => $equipo->id,
            'user_id' => $estudiante->id,
            'fecha_prestamo' => now()->subDay()->toDateString(),
            'fecha_devolucion' => now()->toDateString(),
        ]);

        $this->actingAs($encargado)
            ->patch(route('prestamos.devolver', $prestamo))
            ->assertRedirect(route('prestamos.index'))
            ->assertSessionHas('error');
    }

    public function test_un_estudiante_no_puede_acceder_a_la_gestion_de_prestamos(): void
    {
        $estudiante = $this->crearEstudiante();

        $this->actingAs($estudiante)
            ->get(route('prestamos.index'))
            ->assertRedirect(route('panel'))
            ->assertSessionHas('error');
    }
}