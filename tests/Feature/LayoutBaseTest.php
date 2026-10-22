<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class LayoutBaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Se simulan las rutas que registrarán los demás integrantes del
        // equipo para comprobar el menú dinámico del layout.
        Route::middleware('auth')->get('/equipos', fn () => 'ok')->name('equipos.index');
        Route::middleware('auth')->get('/prestamos', fn () => 'ok')->name('prestamos.index');
        Route::middleware('auth')->get('/usuarios', fn () => 'ok')->name('usuarios.index');
        Route::middleware('auth')->get('/prestamos/mis', fn () => 'ok')->name('prestamos.mis');

        // Laravel refresca esta tabla al arrancar la aplicación; en las
        // pruebas hay que hacerlo a mano tras registrar rutas nuevas.
        Route::getRoutes()->refreshNameLookups();
    }

    public function test_el_panel_muestra_el_navbar_con_el_usuario_y_su_rol(): void
    {
        $encargado = User::factory()->create([
            'name' => 'Carlos Encargado',
            'rol' => User::ROL_ENCARGADO,
        ]);

        $this->actingAs($encargado)
            ->get(route('panel'))
            ->assertOk()
            ->assertSee('Carlos Encargado')
            ->assertSee('encargado')
            ->assertSee('Cerrar sesión')
            ->assertSee('name="_token"', false);
    }

    public function test_el_menu_muestra_el_crud_para_el_encargado(): void
    {
        $encargado = User::factory()->create(['rol' => User::ROL_ENCARGADO]);

        $this->actingAs($encargado)
            ->get(route('panel'))
            ->assertOk()
            ->assertSee('Equipos')
            ->assertSee('Préstamos')
            ->assertSee('Usuarios');
    }

    public function test_el_menu_del_estudiante_no_muestra_la_gestion_de_usuarios(): void
    {
        $estudiante = User::factory()->create(['rol' => User::ROL_ESTUDIANTE]);

        $this->actingAs($estudiante)
            ->get(route('panel'))
            ->assertOk()
            ->assertSee('Mis préstamos')
            ->assertDontSee('Usuarios');
    }

    public function test_el_visitante_sin_sesion_es_enviado_al_login(): void
    {
        $this->get(route('panel'))->assertRedirect(route('login'));
    }
}
