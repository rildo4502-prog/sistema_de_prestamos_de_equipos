<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_los_visitantes_sin_sesion_son_enviados_al_login(): void
    {
        $this->get('/')->assertRedirect(route('login'));
    }

    public function test_el_panel_se_muestra_para_un_usuario_autenticado(): void
    {
        $encargado = User::factory()->create(['rol' => User::ROL_ENCARGADO]);

        $this->actingAs($encargado)->get('/')->assertOk();
    }
}
