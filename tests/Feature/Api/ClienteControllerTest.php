<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Cliente;
use App\Models\ClienteInstancia;
use App\Models\Desarrollo;
use App\Models\DesarrolloVersion;
use App\Models\DesarrolloActual;
use App\Models\ClienteDesarrollo;
use App\Models\ClienteDesarrolloActual;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClienteControllerTest extends TestCase
{
    use RefreshDatabase;

    private $cliente;
    private $tokenCliente;
    private $instancia;
    private $tokenInstancia;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cliente = Cliente::create([
            'nombre' => 'Cliente Test',
            'codigo' => 'CLI-TEST',
            'version_a3erp' => '12.5.0',
            'activo' => true,
        ]);
        $this->tokenCliente = $this->cliente->createToken('cliente-token', ['cliente'])->plainTextToken;

        $this->instancia = ClienteInstancia::create([
            'id_cliente' => $this->cliente->id,
            'nombre' => 'PC-TEST',
            'host' => 'HOST-TEST',
            'tipo' => 'cliente',
            'ip' => '192.168.1.1',
        ]);
        $this->tokenInstancia = $this->instancia->createToken('instancia-token', ['instancia'])->plainTextToken;
    }

    public function test_obtener_cliente_con_token_cliente()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->tokenCliente,
            'Accept' => 'application/json',
        ])->getJson('/api/v1/cliente');

        $response->assertStatus(200)
                 ->assertJsonFragment(['nombre' => 'Cliente Test']);
    }

    public function test_obtener_cliente_con_token_instancia_devuelve_403()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->tokenInstancia,
            'Accept' => 'application/json',
        ])->getJson('/api/v1/cliente');

        $response->assertStatus(403);
    }

    public function test_registrar_instancia_con_token_cliente()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->tokenCliente,
            'Accept' => 'application/json',
        ])->postJson('/api/v1/instancia', [
            'nombre' => 'PC-NUEVA',
            'host' => 'HOST-NUEVA',
            'tipo' => 'cliente',
            'ip' => '192.168.1.50',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['id_instancia', 'token']);
    }

    public function test_registrar_instancia_con_token_instancia_devuelve_403()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->tokenInstancia,
            'Accept' => 'application/json',
        ])->postJson('/api/v1/instancia', [
            'nombre' => 'PC-NUEVA',
            'host' => 'HOST-NUEVA',
            'tipo' => 'cliente',
            'ip' => '192.168.1.50',
        ]);

        $response->assertStatus(403);
    }

    public function test_consultar_actualizaciones_con_token_instancia()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->tokenInstancia,
            'Accept' => 'application/json',
        ])->getJson('/api/v1/actualizaciones');

        $response->assertStatus(200);
    }

    public function test_consultar_actualizaciones_con_token_cliente_devuelve_403()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->tokenCliente,
            'Accept' => 'application/json',
        ])->getJson('/api/v1/actualizaciones');

        $response->assertStatus(403);
    }

    public function test_registrar_resultado_instalacion_ok()
    {
        $dev = Desarrollo::create(['nombre' => 'Dev Test', 'descripcion' => 'Test', 'tipo' => 'Desarrollo', 'activo' => true]);
        $version = DesarrolloVersion::create(['id_dev' => $dev->id, 'version_major' => 1, 'version_minor' => 0, 'version_patch' => 0, 'descripcion_cambios' => 'Test', 'requiere_parada' => false, 'version_a3erp_min' => '12.0.0']);
        DesarrolloActual::create(['id_dev' => $dev->id, 'id_dev_version' => $version->id]);
        $licencia = ClienteDesarrollo::create(['id_cliente' => $this->cliente->id, 'id_dev' => $dev->id, 'activo' => true]);
        ClienteDesarrolloActual::create(['id_cliente_desarrollo' => $licencia->id, 'id_dev_version' => $version->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->tokenInstancia,
            'Accept' => 'application/json',
        ])->postJson('/api/v1/instalaciones/resultado', [
            'id_dev' => $dev->id,
            'id_dev_version' => $version->id,
            'resultado' => 'ok',
            'observaciones' => 'Instalación correcta',
        ]);

        $response->assertStatus(201);
    }

    public function test_registrar_resultado_desarrollo_no_contratado_devuelve_403()
    {
        $otroCliente = Cliente::create([
            'nombre' => 'Otro Cliente',
            'codigo' => 'CLI-OTRO',
            'version_a3erp' => '12.0.0',
            'activo' => true,
        ]);
        $dev = Desarrollo::create(['nombre' => 'Dev No Contratado', 'descripcion' => 'Test', 'tipo' => 'Desarrollo', 'activo' => true]);
        $version = DesarrolloVersion::create(['id_dev' => $dev->id, 'version_major' => 1, 'version_minor' => 0, 'version_patch' => 0, 'descripcion_cambios' => 'Test', 'requiere_parada' => false, 'version_a3erp_min' => '12.0.0']);
        $licenciaAjena = ClienteDesarrollo::create(['id_cliente' => $otroCliente->id, 'id_dev' => $dev->id, 'activo' => true]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->tokenInstancia,
            'Accept' => 'application/json',
        ])->postJson('/api/v1/instalaciones/resultado', [
            'id_dev' => $dev->id,
            'id_dev_version' => $version->id,
            'resultado' => 'ok',
            'observaciones' => 'Test',
        ]);

        $response->assertStatus(403);
    }
}
