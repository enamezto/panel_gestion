<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use App\Models\Cliente;
use App\Models\ClienteInstancia;
use App\Models\ClienteDesarrollo;
use App\Models\ClienteDesarrolloActual;
use App\Models\ClienteDesarrolloVersion;
use App\Models\ClienteInstanciaVersion;

use App\Models\Desarrollo;
use App\Models\DesarrolloVersion;
use App\Models\DesarrolloActual;
use App\Models\DesarrolloDependencia;

use App\Models\AdjuntoTipo;
use App\Models\Adjunto;
use App\Models\AdjuntoVersion;
use App\Models\DesarrolloAdjunto;

class DatosPruebaSeeder2 extends Seeder
{
    public function run(): void
    {
        // Limpiar tablas en orden inverso a las dependencias
        DB::statement('PRAGMA foreign_keys = OFF');

        ClienteInstanciaVersion::truncate();
        ClienteDesarrolloVersion::truncate();
        ClienteDesarrolloActual::truncate();
        ClienteDesarrollo::truncate();
        ClienteInstancia::truncate();
        Cliente::truncate();

        DesarrolloAdjunto::truncate();
        AdjuntoVersion::truncate();
        Adjunto::truncate();
        AdjuntoTipo::truncate();

        DesarrolloActual::truncate();
        DesarrolloDependencia::truncate();
        DesarrolloVersion::truncate();
        Desarrollo::truncate();

        DB::statement('PRAGMA foreign_keys = ON');

        // ====================================================
        // 1. TIPOS DE ADJUNTO (catálogo base)
        // ====================================================
        $tipoBinario     = AdjuntoTipo::create(['nombre' => 'Binario']);
        $tipoDiccionario = AdjuntoTipo::create(['nombre' => 'Diccionario']);
        $tipoMenu        = AdjuntoTipo::create(['nombre' => 'Menu']);
        $tipoImagen      = AdjuntoTipo::create(['nombre' => 'Imagen']);

        // ====================================================
        // 2. CATÁLOGO DE DESARROLLOS (Desarrollos disponibles)
        // ====================================================
        $devFito = Desarrollo::create([
            'nombre' => 'Fitosanitarios a3ERP',
            'descripcion' => 'Desarrollo para gestión de productos fitosanitarios',
            'tipo' => 'Desarrollo',
            'activo' => true,
        ]);

        $devTraza = Desarrollo::create([
            'nombre' => 'Trazabilidad Avanzada',
            'descripcion' => 'Trazabilidad completa de lotes y caducidades',
            'tipo' => 'Desarrollo',
            'activo' => true,
        ]);

        $devLogistica = Desarrollo::create([
            'nombre' => 'Logística Integrada',
            'descripcion' => 'Gestión de almacenes y rutas de reparto',
            'tipo' => 'Desarrollo',
            'activo' => true,
        ]);

        $devReportes = Desarrollo::create([
            'nombre' => 'Reportes Avanzados',
            'descripcion' => 'Informes personalizados y dashboards',
            'tipo' => 'Desarrollo',
            'activo' => true,
        ]);

        // ====================================================
        // 3. VERSIONES DE LOS DESARROLLOS
        // ====================================================
        // Fitosanitarios: v1.0.0 (antigua) y v1.1.0 (actual)
        $fitoV1 = DesarrolloVersion::create([
            'id_dev' => $devFito->id, 'version_major' => 1, 'version_minor' => 0, 'version_patch' => 0,
            'descripcion_cambios' => 'Versión inicial', 'requiere_parada' => false,
            'version_a3erp_min' => '12.0.0',
        ]);
        $fitoV2 = DesarrolloVersion::create([
            'id_dev' => $devFito->id, 'version_major' => 1, 'version_minor' => 1, 'version_patch' => 0,
            'descripcion_cambios' => 'Mejoras de rendimiento y nuevos informes', 'requiere_parada' => false,
            'version_a3erp_min' => '12.0.0',
        ]);

        // Trazabilidad: v1.0.0 (única)
        $trazaV1 = DesarrolloVersion::create([
            'id_dev' => $devTraza->id, 'version_major' => 1, 'version_minor' => 0, 'version_patch' => 0,
            'descripcion_cambios' => 'Lanzamiento inicial', 'requiere_parada' => true,
            'version_a3erp_min' => '12.0.0',
        ]);

        // Logística: v2.0.0 y v2.1.0
        $logV1 = DesarrolloVersion::create([
            'id_dev' => $devLogistica->id, 'version_major' => 2, 'version_minor' => 0, 'version_patch' => 0,
            'descripcion_cambios' => 'Refactorización completa', 'requiere_parada' => true,
            'version_a3erp_min' => '12.0.0',
        ]);
        $logV2 = DesarrolloVersion::create([
            'id_dev' => $devLogistica->id, 'version_major' => 2, 'version_minor' => 1, 'version_patch' => 0,
            'descripcion_cambios' => 'Optimización rutas', 'requiere_parada' => false,
            'version_a3erp_min' => '12.0.0',
        ]);

        // Reportes: v3.0.0 (única)
        $repV1 = DesarrolloVersion::create([
            'id_dev' => $devReportes->id, 'version_major' => 3, 'version_minor' => 0, 'version_patch' => 0,
            'descripcion_cambios' => 'Versión estable', 'requiere_parada' => false,
            'version_a3erp_min' => '12.0.0',
        ]);

        // ====================================================
        // 4. VERSIONES VIGENTES (caché DesarrolloActual)
        // ====================================================
        DesarrolloActual::create(['id_dev' => $devFito->id,      'id_dev_version' => $fitoV2->id]);
        DesarrolloActual::create(['id_dev' => $devTraza->id,     'id_dev_version' => $trazaV1->id]);
        DesarrolloActual::create(['id_dev' => $devLogistica->id, 'id_dev_version' => $logV2->id]);
        DesarrolloActual::create(['id_dev' => $devReportes->id,  'id_dev_version' => $repV1->id]);

        // ====================================================
        // 5. CLIENTES
        // ====================================================
        $cliente1 = Cliente::create([
            'nombre' => 'Bodegas Riojanas SA',
            'codigo' => 'CLI-001',
            'version_a3erp' => '12.5.0',
            'activo' => true,
        ]);

        $cliente2 = Cliente::create([
            'nombre' => 'Distribuciones Norte',
            'codigo' => 'CLI-002',
            'version_a3erp' => '12.4.0',
            'activo' => true,
        ]);

        $cliente3 = Cliente::create([
            'nombre' => 'Frutas del Ebro',
            'codigo' => 'CLI-003',
            'version_a3erp' => '12.5.0',
            'activo' => true,
        ]);

        $cliente4 = Cliente::create([
            'nombre' => 'Logística Pirineos',
            'codigo' => 'CLI-004',
            'version_a3erp' => '12.3.0',
            'activo' => true,
        ]);

        $cliente5 = Cliente::create([
            'nombre' => 'Comercial Antigua SL',
            'codigo' => 'CLI-005',
            'version_a3erp' => '11.0.0',
            'activo' => false, // Inactivo (cancelado)
        ]);

        // ====================================================
        // 6. INSTANCIAS (PCs de cada cliente)
        // ====================================================
        // Cliente 1: 3 PCs, conectados recientemente
        $inst1A = ClienteInstancia::create([
            'id_cliente' => $cliente1->id, 'nombre' => 'PC-CONTABILIDAD',
            'host' => 'PC-CONT-01', 'ip' => '192.168.1.10',
            'ultima_conexion' => Carbon::now()->subHours(2),
        ]);
        $inst1B = ClienteInstancia::create([
            'id_cliente' => $cliente1->id, 'nombre' => 'PC-ALMACEN',
            'host' => 'PC-ALM-01', 'ip' => '192.168.1.11',
            'ultima_conexion' => Carbon::now()->subHours(5),
        ]);
        $inst1C = ClienteInstancia::create([
            'id_cliente' => $cliente1->id, 'nombre' => 'PC-DIRECCION',
            'host' => 'PC-DIR-01', 'ip' => '192.168.1.12',
            'ultima_conexion' => Carbon::now()->subDay(),
        ]);

        // Cliente 2: 2 PCs, uno conectado y otro hace tiempo
        $inst2A = ClienteInstancia::create([
            'id_cliente' => $cliente2->id, 'nombre' => 'PC-OFICINA',
            'host' => 'PC-OFI-01', 'ip' => '10.0.0.20',
            'ultima_conexion' => Carbon::now()->subHours(3),
        ]);
        $inst2B = ClienteInstancia::create([
            'id_cliente' => $cliente2->id, 'nombre' => 'PC-ALMACEN-NORTE',
            'host' => 'PC-ALM-02', 'ip' => '10.0.0.21',
            'ultima_conexion' => Carbon::now()->subDays(45), // Inactiva
        ]);

        // Cliente 3: 1 PC con error reciente
        $inst3A = ClienteInstancia::create([
            'id_cliente' => $cliente3->id, 'nombre' => 'PC-PRINCIPAL',
            'host' => 'PC-PRIN-01', 'ip' => '172.16.0.5',
            'ultima_conexion' => Carbon::now()->subHours(1),
        ]);

        // Cliente 4: 2 PCs (servidor + cliente)
        $inst4A = ClienteInstancia::create([
            'id_cliente' => $cliente4->id, 'nombre' => 'SERVIDOR-PIR',
            'host' => 'SRV-PIR-01', 'ip' => '192.168.50.1',
            'ultima_conexion' => Carbon::now()->subDays(2),
        ]);
        $inst4B = ClienteInstancia::create([
            'id_cliente' => $cliente4->id, 'nombre' => 'PC-RECEPCION',
            'host' => 'PC-REC-01', 'ip' => '192.168.50.10',
            'ultima_conexion' => Carbon::now()->subDays(2),
        ]);

        // ====================================================
        // 7. LICENCIAS (qué desarrollos tiene contratados cada cliente)
        // ====================================================
        // Cliente 1: Fitosanitarios + Trazabilidad
        $lic1A = ClienteDesarrollo::create(['id_cliente' => $cliente1->id, 'id_dev' => $devFito->id, 'activo' => true]);
        $lic1B = ClienteDesarrollo::create(['id_cliente' => $cliente1->id, 'id_dev' => $devTraza->id, 'activo' => true]);

        // Cliente 2: Trazabilidad + Logística
        $lic2A = ClienteDesarrollo::create(['id_cliente' => $cliente2->id, 'id_dev' => $devTraza->id, 'activo' => true]);
        $lic2B = ClienteDesarrollo::create(['id_cliente' => $cliente2->id, 'id_dev' => $devLogistica->id, 'activo' => true]);

        // Cliente 3: Fitosanitarios + Reportes
        $lic3A = ClienteDesarrollo::create(['id_cliente' => $cliente3->id, 'id_dev' => $devFito->id, 'activo' => true]);
        $lic3B = ClienteDesarrollo::create(['id_cliente' => $cliente3->id, 'id_dev' => $devReportes->id, 'activo' => true]);

        // Cliente 4: Logística + Reportes
        $lic4A = ClienteDesarrollo::create(['id_cliente' => $cliente4->id, 'id_dev' => $devLogistica->id, 'activo' => true]);
        $lic4B = ClienteDesarrollo::create(['id_cliente' => $cliente4->id, 'id_dev' => $devReportes->id, 'activo' => true]);

        // ====================================================
        // 8. ESTADO ACTUAL DE CADA LICENCIA (qué versión tiene instalada)
        // ====================================================
        // Cliente 1: TODO ACTUALIZADO ✅
        ClienteDesarrolloActual::create([
            'id_cliente_desarrollo' => $lic1A->id, 'id_dev_version' => $fitoV2->id, // Última
        ]);
        ClienteDesarrolloActual::create([
            'id_cliente_desarrollo' => $lic1B->id, 'id_dev_version' => $trazaV1->id, // Última
        ]);

        // Cliente 2: DESACTUALIZADO ⚠️ (Logística está en v2.0.0 cuando ya hay v2.1.0)
        ClienteDesarrolloActual::create([
            'id_cliente_desarrollo' => $lic2A->id, 'id_dev_version' => $trazaV1->id, // Última
        ]);
        ClienteDesarrolloActual::create([
            'id_cliente_desarrollo' => $lic2B->id, 'id_dev_version' => $logV1->id, // ANTIGUA (debería ser logV2)
        ]);

        // Cliente 3: ACTUALIZADO pero con error reciente
        ClienteDesarrolloActual::create([
            'id_cliente_desarrollo' => $lic3A->id, 'id_dev_version' => $fitoV2->id, // Última
        ]);
        ClienteDesarrolloActual::create([
            'id_cliente_desarrollo' => $lic3B->id, 'id_dev_version' => $repV1->id, // Última
        ]);

        // Cliente 4: DESACTUALIZADO ⚠️ (Logística en v2.0.0 también)
        ClienteDesarrolloActual::create([
            'id_cliente_desarrollo' => $lic4A->id, 'id_dev_version' => $logV1->id, // ANTIGUA
        ]);
        ClienteDesarrolloActual::create([
            'id_cliente_desarrollo' => $lic4B->id, 'id_dev_version' => $repV1->id, // Última
        ]);

        // ====================================================
        // 9. HISTÓRICO DE INSTALACIONES (cliente_instancia_versiones)
        // ====================================================
        // Cliente 1: instalaciones exitosas recientes
        ClienteInstanciaVersion::create([
            'id_instancia' => $inst1A->id, 'id_cliente_desarrollo' => $lic1A->id,
            'id_dev_version' => $fitoV1->id, 'fecha_actualizacion' => Carbon::now()->subDays(30),
            'resultado' => 'ok', 'observaciones' => 'Instalación inicial',
        ]);
        ClienteInstanciaVersion::create([
            'id_instancia' => $inst1A->id, 'id_cliente_desarrollo' => $lic1A->id,
            'id_dev_version' => $fitoV2->id, 'fecha_actualizacion' => Carbon::now()->subDays(5),
            'resultado' => 'ok', 'observaciones' => 'Actualización a v1.1.0',
        ]);
        ClienteInstanciaVersion::create([
            'id_instancia' => $inst1B->id, 'id_cliente_desarrollo' => $lic1B->id,
            'id_dev_version' => $trazaV1->id, 'fecha_actualizacion' => Carbon::now()->subDays(15),
            'resultado' => 'ok', 'observaciones' => 'Instalación correcta',
        ]);

        // Cliente 2: instalaciones antiguas
        ClienteInstanciaVersion::create([
            'id_instancia' => $inst2A->id, 'id_cliente_desarrollo' => $lic2B->id,
            'id_dev_version' => $logV1->id, 'fecha_actualizacion' => Carbon::now()->subDays(60),
            'resultado' => 'ok', 'observaciones' => 'Instalación inicial Logística v2.0.0',
        ]);

        // Cliente 3: ERROR RECIENTE 🔴
        ClienteInstanciaVersion::create([
            'id_instancia' => $inst3A->id, 'id_cliente_desarrollo' => $lic3A->id,
            'id_dev_version' => $fitoV2->id, 'fecha_actualizacion' => Carbon::now()->subDays(2),
            'resultado' => 'error', 'observaciones' => 'Fallo en la instalación: dependencia no encontrada',
        ]);
        ClienteInstanciaVersion::create([
            'id_instancia' => $inst3A->id, 'id_cliente_desarrollo' => $lic3B->id,
            'id_dev_version' => $repV1->id, 'fecha_actualizacion' => Carbon::now()->subDays(10),
            'resultado' => 'ok', 'observaciones' => 'Reportes instalados',
        ]);

        // Cliente 4: instalaciones antiguas
        ClienteInstanciaVersion::create([
            'id_instancia' => $inst4A->id, 'id_cliente_desarrollo' => $lic4A->id,
            'id_dev_version' => $logV1->id, 'fecha_actualizacion' => Carbon::now()->subDays(90),
            'resultado' => 'ok', 'observaciones' => 'Instalación inicial',
        ]);
        ClienteInstanciaVersion::create([
            'id_instancia' => $inst4A->id, 'id_cliente_desarrollo' => $lic4B->id,
            'id_dev_version' => $repV1->id, 'fecha_actualizacion' => Carbon::now()->subDays(20),
            'resultado' => 'ok', 'observaciones' => 'Reportes instalados',
        ]);

        $this->command->info('Datos de prueba generados correctamente:');
        $this->command->info('- 5 clientes (4 activos, 1 inactivo)');
        $this->command->info('- 9 instancias (PCs)');
        $this->command->info('- 4 desarrollos con versiones');
        $this->command->info('- 8 licencias activas');
        $this->command->info('- Cliente 1: ACTUALIZADO');
        $this->command->info('- Cliente 2: DESACTUALIZADO (Logística antigua)');
        $this->command->info('- Cliente 3: ERROR RECIENTE');
        $this->command->info('- Cliente 4: DESACTUALIZADO (Logística antigua)');
    }
}
