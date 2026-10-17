<?php

namespace Database\Seeders;

use App\Models\Equipo;
use Illuminate\Database\Seeder;

class EquipoSeeder extends Seeder
{
    public function run(): void
    {
        $equipos = [
            ['LAP-001', 'Laptop Dell Inspiron 15',      'Laptop'],
            ['LAP-002', 'Laptop Lenovo ThinkPad E14',   'Laptop'],
            ['LAP-003', 'Laptop HP ProBook 450',        'Laptop'],
            ['PRO-001', 'Proyector Epson X41',          'Proyector'],
            ['PRO-002', 'Proyector BenQ MS550',         'Proyector'],
            ['ARD-001', 'Kit Arduino UNO Starter',      'Kit Arduino'],
            ['ARD-002', 'Kit Arduino Mega 2560',        'Kit Arduino'],
            ['MUL-001', 'Multímetro Fluke 117',         'Multímetro'],
            ['MUL-002', 'Multímetro Uni-T UT61E',       'Multímetro'],
            ['OSC-001', 'Osciloscopio Rigol DS1054Z',   'Osciloscopio'],
        ];

        foreach ($equipos as [$codigo, $nombre, $categoria]) {
            Equipo::updateOrCreate(
                ['codigo' => $codigo],
                ['nombre' => $nombre, 'categoria' => $categoria, 'estado' => Equipo::DISPONIBLE]
            );
        }
    }
}
