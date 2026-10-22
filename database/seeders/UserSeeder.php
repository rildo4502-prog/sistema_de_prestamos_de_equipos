<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Contraseña de prueba para todos: "password" (solo para desarrollo).
        // El cast 'hashed' del modelo User la encripta automáticamente.
        $usuarios = [
            ['name' => 'Carlos Encargado', 'email' => 'encargado@lab.test', 'rol' => User::ROL_ENCARGADO],
            ['name' => 'Ana Torres',       'email' => 'ana@lab.test',       'rol' => User::ROL_ESTUDIANTE],
            ['name' => 'Luis Quispe',      'email' => 'luis@lab.test',      'rol' => User::ROL_ESTUDIANTE],
            ['name' => 'María Flores',     'email' => 'maria@lab.test',     'rol' => User::ROL_ESTUDIANTE],
            ['name' => 'Bryan Astete',     'email' => 'brya@lab.test',     'rol' => User::ROL_ESTUDIANTE],
            ['name' => 'Kevin Ayaque',     'email' => 'kev@lab.test',     'rol' => User::ROL_ESTUDIANTE],

        ];

        foreach ($usuarios as $datos) {
            // updateOrCreate: se puede correr el seeder varias veces sin duplicar.
            User::updateOrCreate(
                ['email' => $datos['email']],
                $datos + ['password' => 'password']
            );
        }
    }
}
