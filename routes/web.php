<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PanelController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/', PanelController::class)->name('panel');
});

/*
 * Rutas de administración (equipos, préstamos, usuarios):
 *
 * Route::middleware(['auth', 'rol.encargado'])->group(function () {
 *     Route::resource('equipos', EquipoController::class);
 * });
 */
