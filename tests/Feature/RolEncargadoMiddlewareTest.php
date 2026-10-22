<?php

namespace Tests\Feature;

use App\Http\Middleware\RolEncargado;
use App\Models\User;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RolEncargadoMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ruta protegida sólo para el Encargado, como las secciones de
        // administración de equipos, préstamos y usuarios.
        Route::middleware(['auth', 'rol.encargado'])
            ->get('/prueba-encargado', fn () => 'ok')
            ->name('prueba.encargado');

        // Ruta con el middleware solo, para comprobar la respuesta ante
        // un visitante sin sesión.
        Route::middleware('rol.encargado')
            ->get('/prueba-solo-rol', fn () => 'ok')
            ->name('prueba.solo.rol');

        // Actualiza la tabla de nombres de rutas (Laravel lo hace al arrancar).
        Route::getRoutes()->refreshNameLookups();
    }

    public function test_el_alias_rol_encargado_apunta_al_middleware(): void
    {
        $aliases = app(Kernel::class)->getMiddlewareAliases();

        $this->assertSame(RolEncargado::class, $aliases['rol.encargado']);
    }

    public function test_un_visitante_sin_sesion_es_enviado_al_login(): void
    {
        $this->get('/prueba-solo-rol')
            ->assertRedirect(route('login'))
            ->assertSessionHas('error', 'Debes iniciar sesión para acceder a esta sección.');
    }

    public function test_el_encargado_puede_acceder_a_las_rutas_reservadas(): void
    {
        $encargado = User::factory()->create(['rol' => User::ROL_ENCARGADO]);

        $this->actingAs($encargado)
            ->get('/prueba-encargado')
            ->assertOk()
            ->assertSee('ok');
    }

    public function test_el_estudiante_es_rechazado_con_un_alerta_flash(): void
    {
        $estudiante = User::factory()->create(['rol' => User::ROL_ESTUDIANTE]);

        $response = $this->actingAs($estudiante)->get('/prueba-encargado');

        $response->assertRedirect(route('panel'));
        $response->assertSessionHas('error', 'No tienes permisos de Encargado para acceder a esta sección.');
    }

    public function test_el_alerta_flash_se_muestra_en_el_panel(): void
    {
        $estudiante = User::factory()->create(['rol' => User::ROL_ESTUDIANTE]);

        // El estudiante intenta entrar a una ruta restringida...
        $this->actingAs($estudiante)
            ->get('/prueba-encargado')
            ->assertRedirect(route('panel'));

        // ...y el aviso se muestra en el panel al que es redirigido.
        $this->actingAs($estudiante)
            ->get(route('panel'))
            ->assertOk()
            ->assertSee('No tienes permisos de Encargado para acceder a esta sección.')
            ->assertSee('alert-danger', false);
    }
}
