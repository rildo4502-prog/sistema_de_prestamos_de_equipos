<?php

namespace Tests\Feature;

use App\Models\Equipo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EquipoTest extends TestCase
{
    use RefreshDatabase;

    private function crearEncargado(): User
    {
        return User::factory()->create([
            'rol' => User::ROL_ENCARGADO,
        ]);
    }

    public function test_el_encargado_puede_ver_la_lista_de_equipos(): void
    {
        $encargado = $this->crearEncargado();

        Equipo::create([
            'codigo' => 'EQ-001',
            'nombre' => 'Laptop de prueba',
            'categoria' => 'Laptop',
            'estado' => Equipo::DISPONIBLE,
        ]);

        $this->actingAs($encargado)
            ->get(route('equipos.index'))
            ->assertOk()
            ->assertSee('EQ-001')
            ->assertSee('Laptop de prueba');
    }

    public function test_el_encargado_puede_registrar_un_equipo(): void
    {
        $encargado = $this->crearEncargado();

        $respuesta = $this->actingAs($encargado)
            ->post(route('equipos.store'), [
                'codigo' => 'EQ-002',
                'nombre' => 'Proyector Epson',
                'categoria' => 'Proyector',
            ]);

        $respuesta
            ->assertRedirect(route('equipos.index'))
            ->assertSessionHas('success', 'Equipo registrado correctamente.');

        $this->assertDatabaseHas('equipos', [
            'codigo' => 'EQ-002',
            'nombre' => 'Proyector Epson',
            'categoria' => 'Proyector',
            'estado' => Equipo::DISPONIBLE,
        ]);
    }

    public function test_el_encargado_puede_editar_un_equipo(): void
    {
        $encargado = $this->crearEncargado();

        $equipo = Equipo::create([
            'codigo' => 'EQ-003',
            'nombre' => 'Multímetro antiguo',
            'categoria' => 'Multímetro',
            'estado' => Equipo::DISPONIBLE,
        ]);

        $respuesta = $this->actingAs($encargado)
            ->put(route('equipos.update', $equipo), [
                'codigo' => 'EQ-003',
                'nombre' => 'Multímetro actualizado',
                'categoria' => 'Multímetro',
            ]);

        $respuesta
            ->assertRedirect(route('equipos.index'))
            ->assertSessionHas('success', 'Equipo actualizado correctamente.');

        $this->assertDatabaseHas('equipos', [
            'id' => $equipo->id,
            'nombre' => 'Multímetro actualizado',
        ]);
    }

    public function test_el_encargado_puede_eliminar_un_equipo_disponible(): void
    {
        $encargado = $this->crearEncargado();

        $equipo = Equipo::create([
            'codigo' => 'EQ-004',
            'nombre' => 'Osciloscopio',
            'categoria' => 'Osciloscopio',
            'estado' => Equipo::DISPONIBLE,
        ]);

        $respuesta = $this->actingAs($encargado)
            ->delete(route('equipos.destroy', $equipo));

        $respuesta
            ->assertRedirect(route('equipos.index'))
            ->assertSessionHas('success', 'Equipo eliminado correctamente.');

        $this->assertDatabaseMissing('equipos', [
            'id' => $equipo->id,
        ]);
    }

    public function test_no_se_puede_eliminar_un_equipo_prestado(): void
    {
        $encargado = $this->crearEncargado();

        $equipo = Equipo::create([
            'codigo' => 'EQ-005',
            'nombre' => 'Laptop prestada',
            'categoria' => 'Laptop',
            'estado' => Equipo::PRESTADO,
        ]);

        $respuesta = $this->actingAs($encargado)
            ->delete(route('equipos.destroy', $equipo));

        $respuesta
            ->assertRedirect(route('equipos.index'))
            ->assertSessionHas(
                'error',
                'No se puede eliminar un equipo que está prestado.'
            );

        $this->assertDatabaseHas('equipos', [
            'id' => $equipo->id,
            'estado' => Equipo::PRESTADO,
        ]);
    }

    public function test_un_estudiante_no_puede_acceder_al_crud_de_equipos(): void
    {
        $estudiante = User::factory()->create([
            'rol' => User::ROL_ESTUDIANTE,
        ]);

        $this->actingAs($estudiante)
            ->get(route('equipos.index'))
            ->assertRedirect(route('panel'));
    }
}