<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdjuntoTipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            ['id' => 1, 'nombre' => 'binario'],
            ['id' => 2, 'nombre' => 'diccionario'],
            ['id' => 3, 'nombre' => 'menu'],
            ['id' => 4, 'nombre' => 'imagenes'],
        ];

        foreach ($tipos as $tipo) {
            \App\Models\AdjuntoTipo::updateOrCreate(['id' => $tipo['id']], $tipo);
        }
    }
}
