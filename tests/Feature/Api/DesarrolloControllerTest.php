<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Desarrollo;
use App\Models\DesarrolloVersion;
use App\Models\DesarrolloActual;
use App\Models\AdjuntoTipo;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DesarrolloControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        AdjuntoTipo::create(['nombre' => 'Binario']);
        AdjuntoTipo::create(['nombre' => 'Diccionario']);
        AdjuntoTipo::create(['nombre' => 'Menu']);
        AdjuntoTipo::create(['nombre' => 'Imagen']);
    }

    public function test_registrar_nueva_version()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->postJson('/api/v1/versiones', [
            'moduleName'   => 'Modulo Test',
            'type'         => 'Desarrollo',
            'active'        => 1,
            'major'        => 1,
            'minor'        => 0,
            'patch'        => 0,
            'date'         => '2026-06-01',
            'changes'      => 'Primera versión',
            'requiresStop' => 0,
            'hash'         => md5('modulo'),
            'path'         => 'modulo/bin',
            'adjuntos' => [
                [
                    'tipo'        => 'Binario',
                    'ruta'        => 'modulo/bin/modulo.exe',
                    'major'       => 1,
                    'minor'       => 0,
                    'patch'       => 0,
                    'hash'        => md5('modulo'),
                    'obligatorio' => 1,
                ]
            ],
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('desarrollos', ['nombre' => 'Modulo Test']);
    }

    public function test_rollback_ante_error()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->postJson('/api/v1/versiones', [
            'moduleName'   => 'Modulo Fallido',
            'type'         => 'Desarrollo',
            'major'        => 1,
            'minor'        => 0,
            'patch'        => 0,
            'date'         => '2026-06-01',
            'changes'      => 'Test rollback',
            'requiresStop' => 0,
            'hash'         => md5('modulo'),
            'path'         => 'modulo/bin',
            'adjuntos' => [
                [
                    'tipo'        => 'TipoInexistente',
                    'ruta'        => 'modulo/bin/modulo.exe',
                    'major'       => 1,
                    'minor'       => 0,
                    'patch'       => 0,
                    'hash'        => md5('modulo'),
                    'obligatorio' => 1,
                ]
            ],
        ]);

        $response->assertStatus(500);
        $this->assertDatabaseMissing('desarrollos', ['nombre' => 'Modulo Fallido']);
    }

    public function test_consultar_ultimas_versiones()
    {
        $dev = Desarrollo::create(['nombre' => 'Dev Test', 'descripcion' => 'Test', 'tipo' => 'Desarrollo', 'activo' => true]);
        $version = DesarrolloVersion::create(['id_dev' => $dev->id, 'version_major' => 1, 'version_minor' => 0, 'version_patch' => 0, 'descripcion_cambios' => 'Test', 'requiere_parada' => false, 'version_a3erp_min' => '12.0.0']);
        DesarrolloActual::create(['id_dev' => $dev->id, 'id_dev_version' => $version->id]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->getJson('/api/v1/desarrollos/ultimas');

        $response->assertStatus(200)
                 ->assertJsonFragment(['nombre' => 'Dev Test']);
    }

    public function test_buscar_en_catalogo()
    {
        $dev = Desarrollo::create(['nombre' => 'Fitosanitarios', 'descripcion' => 'Test', 'tipo' => 'Desarrollo', 'activo' => true]);
        $version = DesarrolloVersion::create(['id_dev' => $dev->id, 'version_major' => 1, 'version_minor' => 0, 'version_patch' => 0, 'descripcion_cambios' => 'Test', 'requiere_parada' => false, 'version_a3erp_min' => '12.0.0']);
        DesarrolloActual::create(['id_dev' => $dev->id, 'id_dev_version' => $version->id]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->getJson('/api/v1/desarrollos/ultimas?buscar=Fito');

        $response->assertStatus(200)
                 ->assertJsonFragment(['nombre' => 'Fitosanitarios']);
    }

    public function test_consultar_historial_versiones()
    {
        $dev = Desarrollo::create(['nombre' => 'Dev Test', 'descripcion' => 'Test', 'tipo' => 'Desarrollo', 'activo' => true]);
        DesarrolloVersion::create(['id_dev' => $dev->id, 'version_major' => 1, 'version_minor' => 0, 'version_patch' => 0, 'descripcion_cambios' => 'Test', 'requiere_parada' => false, 'version_a3erp_min' => '12.0.0']);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->getJson('/api/v1/desarrollos/' . $dev->id . '/versiones');

        $response->assertStatus(200);
    }

    public function test_consultar_desarrollo_inexistente_devuelve_404()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->getJson('/api/v1/desarrollos/9999/versiones');

        $response->assertStatus(404);
    }
}
