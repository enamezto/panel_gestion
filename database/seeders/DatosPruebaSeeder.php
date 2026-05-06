<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatosPruebaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clientes (Acorde a migración 2026_03_27_082808)
        DB::table('clientes')->insert([
            ['id' => 1, 'nombre' => 'SDi Pruebas Internas', 'codigo' => 'CLI001', 'activo' => true],
            ['id' => 2, 'nombre' => 'Talleres Automoción Pepe', 'codigo' => 'CLI002', 'activo' => true],
        ]);

        // 2. Desarrollos (Acorde a migración 2026_03_27_082824)
        DB::table('desarrollos')->insert([
            ['id' => 1, 'nombre' => 'Fitosanitarios a3ERP', 'tipo' => 'Modulo', 'activo' => true],
            ['id' => 2, 'nombre' => 'Gestión Documental Avanzada', 'tipo' => 'Software', 'activo' => true],
        ]);

        // 3. Cliente Desarrollos (Licencias compradas)
        DB::table('cliente_desarrollos')->insert([
            ['id' => 1, 'id_cliente' => 1, 'id_dev' => 1, 'activo' => true],
            ['id' => 2, 'id_cliente' => 2, 'id_dev' => 1, 'activo' => true],
        ]);

        // 4. Versiones de Desarrollo
        DB::table('desarrollo_versiones')->insert([
            [
                'id' => 1,
                'id_dev' => 1,
                'version_major' => 1,
                'version_minor' => 0,
                'version_patch' => 0,
                'descripcion_cambios' => 'Versión inicial del sistema',
                'version_a3erp_min' => '12.0.0',
                'requiere_parada' => false
            ],
            [
                'id' => 2,
                'id_dev' => 1,
                'version_major' => 1,
                'version_minor' => 1,
                'version_patch' => 0,
                'descripcion_cambios' => 'Mejoras de rendimiento',
                'version_a3erp_min' => '12.0.0',
                'requiere_parada' => false
            ]
        ]);

        // 5. Establecer versión actual del catálogo
        DB::table('desarrollo_actuales')->insert([
            ['id_dev' => 1, 'id_dev_version' => 2]
        ]);

        // 6. Tipos de Adjuntos
        DB::table('adjunto_tipos')->insert([
            ['id' => 1, 'nombre' => 'Instalador'],
            ['id' => 2, 'nombre' => 'Diccionario'],
        ]);

        // 7. Adjuntos (Ficheros)
        DB::table('adjuntos')->insert([
            [
                'id' => 1,
                'id_tipo' => 1,
                'arbol_carpeta' => 'Binarios',
                'principal' => 'ppp.exe'
            ],
            [
                'id' => 2,
                'id_tipo' => 2,
                'arbol_carpeta' => 'Menús',
                'principal' => ''
            ],
        ]);

        // 8. Versiones de Adjuntos
        DB::table('adjunto_versiones')->insert([
            ['id' => 1, 'id_adj' => 1, 'version_major' => 1, 'version_minor' => 1, 'version_patch' => 0, 'hash' => 'HASH123'],
            ['id' => 2, 'id_adj' => 2, 'version_major' => 1, 'version_minor' => 1, 'version_patch' => 0, 'hash' => 'HASH456'],
        ]);

        // 9. Relación entre Versión de Desarrollo y Versión de Adjunto
        DB::table('desarrollo_adjuntos')->insert([
            ['id_dev_version' => 2, 'id_adj_version' => 1, 'obligatorio' => true, 'orden' => 1],
            ['id_dev_version' => 2, 'id_adj_version' => 2, 'obligatorio' => false, 'orden' => 2],
        ]);
    }
}
