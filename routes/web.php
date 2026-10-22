<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PanelController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas web
|--------------------------------------------------------------------------
| 'guest'        -> sólo para usuarios sin sesión.
| 'auth'         -> exige sesión iniciada.
| 'rol.encargado'-> alias de App\Http\Middleware\RolEncargado (registrado
|                   en bootstrap/app.php). Sólo Encargado/Administrador.
*/

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    // Panel inicial (respalda el layout base con navbar y sidebar).
    Route::get('/', PanelController::class)->name('panel');
});

/*
|--------------------------------------------------------------------------
| Ejemplo de uso del middleware de rol
|--------------------------------------------------------------------------
| Los integrantes responsables del CRUD de equipos, préstamos y usuarios
| deben envolver sus rutas así:
|
|   Route::middleware(['auth', 'rol.encargado'])->group(function () {
|       Route::resource('equipos', EquipoController::class);
|   });
|
| Para el Estudiante basta con el middleware 'auth' y filtrar los datos
| en el controlador (por ejemplo: Equipo::disponibles()).
*/
