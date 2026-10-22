<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_pantalla_de_login_se_muestra(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('name="_token"', false)
            ->assertSee('Iniciar sesión');
    }

    public function test_un_usuario_puede_iniciar_sesion(): void
    {
        $encargado = User::factory()->create([
            'email' => 'encargado@lab.test',
            'rol' => User::ROL_ENCARGADO,
        ]);

        $this->post(route('login.store'), [
            'email' => 'encargado@lab.test',
            'password' => 'password',
        ])->assertRedirect(route('panel'));

        $this->assertAuthenticatedAs($encargado);
    }

    public function test_las_credenciales_invalidas_muestran_error(): void
    {
        User::factory()->create(['email' => 'encargado@lab.test']);

        $this->post(route('login.store'), [
            'email' => 'encargado@lab.test',
            'password' => 'incorrecta',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_el_usuario_puede_cerrar_sesion(): void
    {
        $encargado = User::factory()->create(['rol' => User::ROL_ENCARGADO]);

        $this->actingAs($encargado)
            ->post(route('logout'))
            ->assertRedirect(route('login'))
            ->assertSessionHas('success');

        $this->assertGuest();
    }
}
