<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrestamoController;

// Login personalizado
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'store'])
        ->name('login.store');
});

// Rutas para usuarios autenticados
Route::middleware('auth')->group(function () {

    // Panel principal
    Route::get('/', PanelController::class)
        ->name('panel');

    // Cerrar sesión
    Route::post('/logout', [LoginController::class, 'destroy'])
        ->name('logout');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

// Solo Encargado
Route::middleware(['auth', 'rol.encargado'])->group(function () {

    // CRUD de equipos
    Route::resource('equipos', EquipoController::class)
        ->except(['show']);

    // Préstamos
    Route::get('/prestamos', [PrestamoController::class, 'index'])
        ->name('prestamos.index');

    Route::post('/prestamos/prestar', [PrestamoController::class, 'prestar'])
        ->name('prestamos.prestar');

    Route::patch('/prestamos/{prestamo}/devolver', [PrestamoController::class, 'devolver'])
        ->name('prestamos.devolver');
});