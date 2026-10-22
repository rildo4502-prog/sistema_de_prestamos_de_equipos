<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LayoutBaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_panel_muestra_el_usuario_y_su_rol(): void
    {
        $encargado = User::factory()->create([
            'name' => 'Carlos Encargado',
            'rol' => User::ROL_ENCARGADO,
        ]);

        $this->actingAs($encargado)
            ->get(route('panel'))
            ->assertOk()
            ->assertSee('Carlos Encargado')
            ->assertSee('Encargado')
            ->assertSee('Cerrar sesión');
    }

    public function test_el_menu_del_encargado_muestra_equipos_y_prestamos(): void
    {
        $encargado = User::factory()->create([
            'rol' => User::ROL_ENCARGADO,
        ]);

        $this->actingAs($encargado)
            ->get(route('panel'))
            ->assertOk()
            ->assertSee('Equipos')
            ->assertSee('Préstamos');
    }

    public function test_el_menu_del_estudiante_no_muestra_opciones_de_gestion(): void
    {
        $estudiante = User::factory()->create([
            'name' => 'Ana Torres',
            'rol' => User::ROL_ESTUDIANTE,
        ]);

        $this->actingAs($estudiante)
            ->get(route('panel'))
            ->assertOk()
            ->assertSee('Ana Torres')
            ->assertSee('Estudiante')
            ->assertDontSee('Gestionar equipos')
            ->assertDontSee('Gestionar préstamos');
    }

    public function test_el_visitante_sin_sesion_es_enviado_al_login(): void
    {
        $this->get(route('panel'))
            ->assertRedirect(route('login'));
    }
}