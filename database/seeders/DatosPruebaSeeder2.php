<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

use App\Models\User;

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
        $faker = Faker::create('es_ES');

        DB::statement('SET session_replication_role = replica;');

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
        User::truncate();

        User::create([
            'name' => 'Técnico SDi',
            'email' => 'tecnico@sdi.es',
            'password' => bcrypt('password123'),
        ]);

        DB::statement('SET session_replication_role = DEFAULT;');

        // ====================================================
        // 1. TIPOS DE ADJUNTO
        // ====================================================
        $tipoBinario    = AdjuntoTipo::create(['nombre' => 'Binario']);
        $tipoDicc       = AdjuntoTipo::create(['nombre' => 'Diccionario']);
        $tipoMenu       = AdjuntoTipo::create(['nombre' => 'Menu']);
        $tipoImagen     = AdjuntoTipo::create(['nombre' => 'Imagen']);

        // ====================================================
        // 2. CATÁLOGO DE DESARROLLOS (70 desarrollos)
        // ====================================================

        // --- 20 desarrollos controlados ---
        $devFito      = Desarrollo::create(['nombre' => 'Fitosanitarios a3ERP',       'descripcion' => 'Gestión de productos fitosanitarios',                    'tipo' => 'Desarrollo',        'activo' => true]);
        $devTraza     = Desarrollo::create(['nombre' => 'Trazabilidad Avanzada',       'descripcion' => 'Trazabilidad completa de lotes y caducidades',           'tipo' => 'Desarrollo',        'activo' => true]);
        $devLogistica = Desarrollo::create(['nombre' => 'Logística Integrada',         'descripcion' => 'Gestión de almacenes y rutas de reparto',                'tipo' => 'Desarrollo',        'activo' => true]);
        $devReportes  = Desarrollo::create(['nombre' => 'Reportes Avanzados',          'descripcion' => 'Informes personalizados y dashboards',                   'tipo' => 'Desarrollo',        'activo' => true]);
        $devContab    = Desarrollo::create(['nombre' => 'Contabilidad Analítica',      'descripcion' => 'Módulo de contabilidad por centros de coste',            'tipo' => 'Desarrollo',        'activo' => true]);
        $devFactura   = Desarrollo::create(['nombre' => 'Facturación Electrónica',     'descripcion' => 'Emisión de facturas electrónicas',                       'tipo' => 'Desarrollo',        'activo' => true]);
        $devRRHH      = Desarrollo::create(['nombre' => 'Recursos Humanos',            'descripcion' => 'Gestión de personal y nóminas',                          'tipo' => 'Desarrollo',        'activo' => true]);
        $devCRM       = Desarrollo::create(['nombre' => 'CRM Comercial',               'descripcion' => 'Gestión de clientes y oportunidades',                    'tipo' => 'Desarrollo',        'activo' => true]);
        $devTPV       = Desarrollo::create(['nombre' => 'TPV Integrado',               'descripcion' => 'Terminal punto de venta integrado con a3ERP',            'tipo' => 'Desarrollo',        'activo' => true]);
        $devEcom      = Desarrollo::create(['nombre' => 'Conector eCommerce',          'descripcion' => 'Integración con tiendas online',                         'tipo' => 'Desarrollo',        'activo' => true]);
        $devBI        = Desarrollo::create(['nombre' => 'Business Intelligence',       'descripcion' => 'Cuadros de mando y KPIs',                                'tipo' => 'Desarrollo',        'activo' => true]);
        $devQR        = Desarrollo::create(['nombre' => 'Etiquetado QR',               'descripcion' => 'Generación de etiquetas con QR',                         'tipo' => 'Desarrollo',        'activo' => true]);
        $devAlmacen   = Desarrollo::create(['nombre' => 'Gestión de Almacén',          'descripcion' => 'Control de stock y ubicaciones',                         'tipo' => 'Desarrollo',        'activo' => true]);
        $devCalidad   = Desarrollo::create(['nombre' => 'Control de Calidad',          'descripcion' => 'Registros de calidad y certificaciones',                 'tipo' => 'Desarrollo',        'activo' => true]);
        $devMante     = Desarrollo::create(['nombre' => 'Mantenimiento',               'descripcion' => 'Gestión de mantenimiento preventivo y correctivo',       'tipo' => 'Desarrollo',        'activo' => true]);
        $devObras     = Desarrollo::create(['nombre' => 'Gestión de Obras',            'descripcion' => 'Control de proyectos y obras',                           'tipo' => 'Desarrollo',        'activo' => true]);
        $devTransporte= Desarrollo::create(['nombre' => 'Transporte y Rutas',          'descripcion' => 'Planificación de rutas de transporte',                   'tipo' => 'Desarrollo',        'activo' => true]);
        $devDocGest   = Desarrollo::create(['nombre' => 'Gestión Documental',          'descripcion' => 'Archivo y gestión de documentos',                        'tipo' => 'Desarrollo',        'activo' => true]);
        $devProd      = Desarrollo::create(['nombre' => 'Control de Producción',       'descripcion' => 'Órdenes de fabricación y producción',                    'tipo' => 'Desarrollo',        'activo' => true]);
        $devLegado    = Desarrollo::create(['nombre' => 'Módulo Legado v1',            'descripcion' => 'Módulo antiguo en proceso de sustitución',               'tipo' => 'Desarrollo',        'activo' => false]);

        // --- 50 desarrollos adicionales con Faker ---
        $nombresExtra = [
            'Gestión de Proyectos', 'Portal del Empleado', 'Módulo de Compras', 'Integración SAP',
            'Control Horario', 'Gestión de Flotas', 'Módulo de Ventas', 'Integración BI Plus',
            'Gestión de Incidencias', 'Portal del Cliente', 'Módulo de Pedidos', 'Integración ERP',
            'Control de Accesos', 'Gestión de Contratos', 'Módulo de Presupuestos', 'Integración CRM',
            'Gestión de Activos', 'Portal de Proveedores', 'Módulo de Facturación', 'Integración WMS',
            'Control de Inventario', 'Gestión de Turnos', 'Módulo de Cobros', 'Integración POS',
            'Gestión de Formación', 'Portal de Empleados', 'Módulo de Pagos', 'Integración MRP',
            'Control de Calidad Plus', 'Gestión de Proyectos Plus', 'Módulo de Nóminas', 'Integración HCM',
            'Gestión de Seguros', 'Portal de Clientes Plus', 'Módulo de Garantías', 'Integración SCM',
            'Control de Mermas', 'Gestión de Expediciones', 'Módulo de Devoluciones', 'Integración PLM',
            'Gestión de Licitaciones', 'Portal de Socios', 'Módulo de Alquileres', 'Integración PDM',
            'Control de Caducidades', 'Gestión de Reservas', 'Módulo de Suscripciones', 'Integración ECM',
            'Gestión de Eventos', 'Módulo de Comunicaciones',
        ];

        $desarrollosExtra = [];
        foreach ($nombresExtra as $nombre) {
            $desarrollosExtra[] = Desarrollo::create([
                'nombre'      => $nombre . ' a3ERP',
                'descripcion' => $faker->sentence(6),
                'tipo'        => $faker->randomElement(['Desarrollo', 'Librería', 'Proceso Externo']),
                'activo'      => $faker->boolean(85),
            ]);
        }

        $todosDesarrollos = array_merge(
            [$devFito, $devTraza, $devLogistica, $devReportes, $devContab,
             $devFactura, $devRRHH, $devCRM, $devTPV, $devEcom,
             $devBI, $devQR, $devAlmacen, $devCalidad, $devMante,
             $devObras, $devTransporte, $devDocGest, $devProd, $devLegado],
            $desarrollosExtra
        );

        // ====================================================
        // 3. VERSIONES DE LOS DESARROLLOS CONTROLADOS
        // ====================================================
        $fitoV1   = DesarrolloVersion::create(['id_dev' => $devFito->id,      'version_major' => 1, 'version_minor' => 0, 'version_patch' => 0, 'descripcion_cambios' => 'Versión inicial',                  'requiere_parada' => false, 'version_a3erp_min' => '12.0.0']);
        $fitoV2   = DesarrolloVersion::create(['id_dev' => $devFito->id,      'version_major' => 1, 'version_minor' => 1, 'version_patch' => 0, 'descripcion_cambios' => 'Mejoras de rendimiento',           'requiere_parada' => false, 'version_a3erp_min' => '12.0.0']);
        $fitoV3   = DesarrolloVersion::create(['id_dev' => $devFito->id,      'version_major' => 1, 'version_minor' => 2, 'version_patch' => 0, 'descripcion_cambios' => 'Nuevos informes de trazabilidad',  'requiere_parada' => false, 'version_a3erp_min' => '12.3.0']);
        $trazaV1  = DesarrolloVersion::create(['id_dev' => $devTraza->id,     'version_major' => 1, 'version_minor' => 0, 'version_patch' => 0, 'descripcion_cambios' => 'Lanzamiento inicial',              'requiere_parada' => true,  'version_a3erp_min' => '12.0.0']);
        $trazaV2  = DesarrolloVersion::create(['id_dev' => $devTraza->id,     'version_major' => 1, 'version_minor' => 1, 'version_patch' => 0, 'descripcion_cambios' => 'Soporte multi-almacén',            'requiere_parada' => false, 'version_a3erp_min' => '12.2.0']);
        $logV1    = DesarrolloVersion::create(['id_dev' => $devLogistica->id, 'version_major' => 2, 'version_minor' => 0, 'version_patch' => 0, 'descripcion_cambios' => 'Refactorización completa',         'requiere_parada' => true,  'version_a3erp_min' => '12.0.0']);
        $logV2    = DesarrolloVersion::create(['id_dev' => $devLogistica->id, 'version_major' => 2, 'version_minor' => 1, 'version_patch' => 0, 'descripcion_cambios' => 'Optimización de rutas',            'requiere_parada' => false, 'version_a3erp_min' => '12.0.0']);
        $repV1    = DesarrolloVersion::create(['id_dev' => $devReportes->id,  'version_major' => 3, 'version_minor' => 0, 'version_patch' => 0, 'descripcion_cambios' => 'Versión estable',                  'requiere_parada' => false, 'version_a3erp_min' => '12.0.0']);
        $repV2    = DesarrolloVersion::create(['id_dev' => $devReportes->id,  'version_major' => 3, 'version_minor' => 1, 'version_patch' => 0, 'descripcion_cambios' => 'Nuevas plantillas',                'requiere_parada' => false, 'version_a3erp_min' => '12.0.0']);
        $contabV1 = DesarrolloVersion::create(['id_dev' => $devContab->id,   'version_major' => 1, 'version_minor' => 0, 'version_patch' => 0, 'descripcion_cambios' => 'Primera versión',                  'requiere_parada' => true,  'version_a3erp_min' => '12.1.0']);
        $contabV2 = DesarrolloVersion::create(['id_dev' => $devContab->id,   'version_major' => 1, 'version_minor' => 1, 'version_patch' => 0, 'descripcion_cambios' => 'Mejoras en cierres contables',     'requiere_parada' => false, 'version_a3erp_min' => '12.1.0']);
        $factV1   = DesarrolloVersion::create(['id_dev' => $devFactura->id,  'version_major' => 2, 'version_minor' => 0, 'version_patch' => 0, 'descripcion_cambios' => 'Soporte FacturaE',                 'requiere_parada' => false, 'version_a3erp_min' => '12.0.0']);
        $rrhhV1   = DesarrolloVersion::create(['id_dev' => $devRRHH->id,     'version_major' => 1, 'version_minor' => 0, 'version_patch' => 0, 'descripcion_cambios' => 'Versión inicial',                  'requiere_parada' => true,  'version_a3erp_min' => '12.2.0']);
        $rrhhV2   = DesarrolloVersion::create(['id_dev' => $devRRHH->id,     'version_major' => 1, 'version_minor' => 1, 'version_patch' => 0, 'descripcion_cambios' => 'Control horario integrado',        'requiere_parada' => false, 'version_a3erp_min' => '12.2.0']);
        $crmV1    = DesarrolloVersion::create(['id_dev' => $devCRM->id,      'version_major' => 1, 'version_minor' => 0, 'version_patch' => 0, 'descripcion_cambios' => 'Primera versión',                  'requiere_parada' => false, 'version_a3erp_min' => '12.0.0']);
        $tpvV1    = DesarrolloVersion::create(['id_dev' => $devTPV->id,      'version_major' => 1, 'version_minor' => 0, 'version_patch' => 0, 'descripcion_cambios' => 'Primera versión',                  'requiere_parada' => true,  'version_a3erp_min' => '12.0.0']);
        $ecomV1   = DesarrolloVersion::create(['id_dev' => $devEcom->id,     'version_major' => 1, 'version_minor' => 0, 'version_patch' => 0, 'descripcion_cambios' => 'Conector WooCommerce',             'requiere_parada' => false, 'version_a3erp_min' => '12.3.0']);
        $biV1     = DesarrolloVersion::create(['id_dev' => $devBI->id,       'version_major' => 2, 'version_minor' => 0, 'version_patch' => 0, 'descripcion_cambios' => 'Nueva versión con Power BI',       'requiere_parada' => false, 'version_a3erp_min' => '12.0.0']);
        $qrV1     = DesarrolloVersion::create(['id_dev' => $devQR->id,       'version_major' => 1, 'version_minor' => 0, 'version_patch' => 0, 'descripcion_cambios' => 'Primera versión',                  'requiere_parada' => false, 'version_a3erp_min' => '12.0.0']);
        $almacenV1= DesarrolloVersion::create(['id_dev' => $devAlmacen->id,  'version_major' => 1, 'version_minor' => 0, 'version_patch' => 0, 'descripcion_cambios' => 'Primera versión',                  'requiere_parada' => true,  'version_a3erp_min' => '12.1.0']);
        $calidadV1= DesarrolloVersion::create(['id_dev' => $devCalidad->id,  'version_major' => 1, 'version_minor' => 0, 'version_patch' => 0, 'descripcion_cambios' => 'Primera versión',                  'requiere_parada' => false, 'version_a3erp_min' => '12.0.0']);
        $manteV1  = DesarrolloVersion::create(['id_dev' => $devMante->id,    'version_major' => 1, 'version_minor' => 0, 'version_patch' => 0, 'descripcion_cambios' => 'Primera versión',                  'requiere_parada' => false, 'version_a3erp_min' => '12.0.0']);
        $obrasV1  = DesarrolloVersion::create(['id_dev' => $devObras->id,    'version_major' => 1, 'version_minor' => 0, 'version_patch' => 0, 'descripcion_cambios' => 'Primera versión',                  'requiere_parada' => true,  'version_a3erp_min' => '12.2.0']);
        $transpV1 = DesarrolloVersion::create(['id_dev' => $devTransporte->id,'version_major'=> 1, 'version_minor' => 0, 'version_patch' => 0, 'descripcion_cambios' => 'Primera versión',                  'requiere_parada' => false, 'version_a3erp_min' => '12.0.0']);
        $docgestV1= DesarrolloVersion::create(['id_dev' => $devDocGest->id,  'version_major' => 1, 'version_minor' => 0, 'version_patch' => 0, 'descripcion_cambios' => 'Primera versión',                  'requiere_parada' => false, 'version_a3erp_min' => '12.0.0']);
        $prodV1   = DesarrolloVersion::create(['id_dev' => $devProd->id,     'version_major' => 1, 'version_minor' => 0, 'version_patch' => 0, 'descripcion_cambios' => 'Primera versión',                  'requiere_parada' => true,  'version_a3erp_min' => '12.3.0']);
        $legadoV1 = DesarrolloVersion::create(['id_dev' => $devLegado->id,   'version_major' => 1, 'version_minor' => 0, 'version_patch' => 0, 'descripcion_cambios' => 'Versión única',                    'requiere_parada' => false, 'version_a3erp_min' => '11.0.0']);

        // --- Versiones para los 50 desarrollos extra con Faker ---
        $versionesExtra = [];
        foreach ($desarrollosExtra as $dev) {
            $numVersiones = $faker->numberBetween(1, 4);
            $ultimaVersion = null;
            for ($v = 1; $v <= $numVersiones; $v++) {
                $ultimaVersion = DesarrolloVersion::create([
                    'id_dev'              => $dev->id,
                    'version_major'       => $v,
                    'version_minor'       => $faker->numberBetween(0, 5),
                    'version_patch'       => $faker->numberBetween(0, 9),
                    'descripcion_cambios' => $faker->sentence(5),
                    'requiere_parada'     => $faker->boolean(25),
                    'version_a3erp_min'   => $faker->randomElement(['11.0.0', '12.0.0', '12.1.0', '12.2.0', '12.3.0']),
                ]);
            }
            $versionesExtra[$dev->id] = $ultimaVersion;
        }

        // ====================================================
        // 4. ADJUNTOS
        // ====================================================
        // Adjuntos para los desarrollos controlados
        $adjFito = Adjunto::create(['id_tipo' => $tipoBinario->id, 'arbol_carpeta' => 'fitosanitarios/bin/fito.exe']);
        $adjFitoDicc = Adjunto::create(['id_tipo' => $tipoDicc->id, 'arbol_carpeta' => 'fitosanitarios/dicc/fito.dic']);
        $adjTraza = Adjunto::create(['id_tipo' => $tipoBinario->id, 'arbol_carpeta' => 'trazabilidad/bin/traza.exe']);
        $adjLog = Adjunto::create(['id_tipo' => $tipoBinario->id, 'arbol_carpeta' => 'logistica/bin/log.exe']);
        $adjLogMenu = Adjunto::create(['id_tipo' => $tipoMenu->id, 'arbol_carpeta' => 'logistica/menu/log.men']);
        $adjRep = Adjunto::create(['id_tipo' => $tipoBinario->id, 'arbol_carpeta' => 'reportes/bin/rep.exe']);
        $adjContab = Adjunto::create(['id_tipo' => $tipoBinario->id, 'arbol_carpeta' => 'contabilidad/bin/cont.exe']);
        $adjFactura = Adjunto::create(['id_tipo' => $tipoBinario->id, 'arbol_carpeta' => 'facturacion/bin/fact.exe']);
        $adjRRHH = Adjunto::create(['id_tipo' => $tipoBinario->id, 'arbol_carpeta' => 'rrhh/bin/rrhh.exe']);
        $adjCRM = Adjunto::create(['id_tipo' => $tipoBinario->id, 'arbol_carpeta' => 'crm/bin/crm.exe']);

        // AdjuntoVersiones y DesarrolloAdjunto para controlados
        $avFitoV1 = AdjuntoVersion::create([					'id_adj'    => $adjFito->id, 'version_major' => 1, 'version_minor' => 0, 'version_patch' => 0, 'hash' => md5('fitoV1'), 'fecha' => Carbon::now()->subDays(90)]);
        DesarrolloAdjunto::create(['id_dev_version' => $fitoV1->id, 'id_adj_version' => $avFitoV1->id, 'obligatorio' => true, 'orden' => 1]);

        $avFitoV2 = AdjuntoVersion::create([					'id_adj'    => $adjFito->id, 'version_major' => 1, 'version_minor' => 1, 'version_patch' => 0, 'hash' => md5('fitoV2'), 'fecha' => Carbon::now()->subDays(60)]);
        $avFitoDiccV2 = AdjuntoVersion::create([					'id_adj'    => $adjFitoDicc->id, 'version_major' => 1, 'version_minor' => 1, 'version_patch' => 0, 'hash' => md5('fitoDiccV2'), 'fecha' => Carbon::now()->subDays(60)]);
        DesarrolloAdjunto::create(['id_dev_version' => $fitoV2->id, 'id_adj_version' => $avFitoV2->id, 'obligatorio' => true, 'orden' => 1]);
        DesarrolloAdjunto::create(['id_dev_version' => $fitoV2->id, 'id_adj_version' => $avFitoDiccV2->id, 'obligatorio' => false, 'orden' => 2]);

        $avFitoV3 = AdjuntoVersion::create([					'id_adj'    => $adjFito->id, 'version_major' => 1, 'version_minor' => 2, 'version_patch' => 0, 'hash' => md5('fitoV3'), 'fecha' => Carbon::now()->subDays(5)]);
        DesarrolloAdjunto::create(['id_dev_version' => $fitoV3->id, 'id_adj_version' => $avFitoV3->id, 'obligatorio' => true, 'orden' => 1]);
        DesarrolloAdjunto::create(['id_dev_version' => $fitoV3->id, 'id_adj_version' => $avFitoDiccV2->id, 'obligatorio' => false, 'orden' => 2]);

        $avTrazaV1 = AdjuntoVersion::create([					'id_adj'    => $adjTraza->id, 'version_major' => 1, 'version_minor' => 0, 'version_patch' => 0, 'hash' => md5('trazaV1'), 'fecha' => Carbon::now()->subDays(80)]);
        DesarrolloAdjunto::create(['id_dev_version' => $trazaV1->id, 'id_adj_version' => $avTrazaV1->id, 'obligatorio' => true, 'orden' => 1]);

        $avTrazaV2 = AdjuntoVersion::create([					'id_adj'    => $adjTraza->id, 'version_major' => 1, 'version_minor' => 1, 'version_patch' => 0, 'hash' => md5('trazaV2'), 'fecha' => Carbon::now()->subDays(30)]);
        DesarrolloAdjunto::create(['id_dev_version' => $trazaV2->id, 'id_adj_version' => $avTrazaV2->id, 'obligatorio' => true, 'orden' => 1]);

        $avLogV1 = AdjuntoVersion::create([					'id_adj'    => $adjLog->id, 'version_major' => 2, 'version_minor' => 0, 'version_patch' => 0, 'hash' => md5('logV1'), 'fecha' => Carbon::now()->subDays(120)]);
        $avLogMenuV1 = AdjuntoVersion::create([					'id_adj'    => $adjLogMenu->id, 'version_major' => 2, 'version_minor' => 0, 'version_patch' => 0, 'hash' => md5('logMenuV1'), 'fecha' => Carbon::now()->subDays(120)]);
        DesarrolloAdjunto::create(['id_dev_version' => $logV1->id, 'id_adj_version' => $avLogV1->id, 'obligatorio' => true, 'orden' => 1]);
        DesarrolloAdjunto::create(['id_dev_version' => $logV1->id, 'id_adj_version' => $avLogMenuV1->id, 'obligatorio' => false, 'orden' => 2]);

        $avLogV2 = AdjuntoVersion::create([					'id_adj'    => $adjLog->id, 'version_major' => 2, 'version_minor' => 1, 'version_patch' => 0, 'hash' => md5('logV2'), 'fecha' => Carbon::now()->subDays(20)]);
        DesarrolloAdjunto::create(['id_dev_version' => $logV2->id, 'id_adj_version' => $avLogV2->id, 'obligatorio' => true, 'orden' => 1]);
        DesarrolloAdjunto::create(['id_dev_version' => $logV2->id, 'id_adj_version' => $avLogMenuV1->id, 'obligatorio' => false, 'orden' => 2]);

        $avRepV1 = AdjuntoVersion::create([					'id_adj'    => $adjRep->id, 'version_major' => 3, 'version_minor' => 0, 'version_patch' => 0, 'hash' => md5('repV1'), 'fecha' => Carbon::now()->subDays(60)]);
        DesarrolloAdjunto::create(['id_dev_version' => $repV1->id, 'id_adj_version' => $avRepV1->id, 'obligatorio' => true, 'orden' => 1]);

        $avRepV2 = AdjuntoVersion::create([					'id_adj'    => $adjRep->id, 'version_major' => 3, 'version_minor' => 1, 'version_patch' => 0, 'hash' => md5('repV2'), 'fecha' => Carbon::now()->subDays(10)]);
        DesarrolloAdjunto::create(['id_dev_version' => $repV2->id, 'id_adj_version' => $avRepV2->id, 'obligatorio' => true, 'orden' => 1]);

        $avContabV1 = AdjuntoVersion::create([					'id_adj'    => $adjContab->id, 'version_major' => 1, 'version_minor' => 0, 'version_patch' => 0, 'hash' => md5('contabV1'), 'fecha' => Carbon::now()->subDays(50)]);
        DesarrolloAdjunto::create(['id_dev_version' => $contabV1->id, 'id_adj_version' => $avContabV1->id, 'obligatorio' => true, 'orden' => 1]);

        $avContabV2 = AdjuntoVersion::create([					'id_adj'    => $adjContab->id, 'version_major' => 1, 'version_minor' => 1, 'version_patch' => 0, 'hash' => md5('contabV2'), 'fecha' => Carbon::now()->subDays(8)]);
        DesarrolloAdjunto::create(['id_dev_version' => $contabV2->id, 'id_adj_version' => $avContabV2->id, 'obligatorio' => true, 'orden' => 1]);

        $avFactV1 = AdjuntoVersion::create([					'id_adj'    => $adjFactura->id, 'version_major' => 2, 'version_minor' => 0, 'version_patch' => 0, 'hash' => md5('factV1'), 'fecha' => Carbon::now()->subDays(40)]);
        DesarrolloAdjunto::create(['id_dev_version' => $factV1->id, 'id_adj_version' => $avFactV1->id, 'obligatorio' => true, 'orden' => 1]);

        $avRrhhV1 = AdjuntoVersion::create([					'id_adj'    => $adjRRHH->id, 'version_major' => 1, 'version_minor' => 0, 'version_patch' => 0, 'hash' => md5('rrhhV1'), 'fecha' => Carbon::now()->subDays(70)]);
        DesarrolloAdjunto::create(['id_dev_version' => $rrhhV1->id, 'id_adj_version' => $avRrhhV1->id, 'obligatorio' => true, 'orden' => 1]);

        $avRrhhV2 = AdjuntoVersion::create([					'id_adj'    => $adjRRHH->id, 'version_major' => 1, 'version_minor' => 1, 'version_patch' => 0, 'hash' => md5('rrhhV2'), 'fecha' => Carbon::now()->subDays(15)]);
        DesarrolloAdjunto::create(['id_dev_version' => $rrhhV2->id, 'id_adj_version' => $avRrhhV2->id, 'obligatorio' => true, 'orden' => 1]);

        $avCrmV1 = AdjuntoVersion::create([					'id_adj'    => $adjCRM->id, 'version_major' => 1, 'version_minor' => 0, 'version_patch' => 0, 'hash' => md5('crmV1'), 'fecha' => Carbon::now()->subDays(35)]);
        DesarrolloAdjunto::create(['id_dev_version' => $crmV1->id, 'id_adj_version' => $avCrmV1->id, 'obligatorio' => true, 'orden' => 1]);

        // Adjuntos para desarrollos extra con Faker
        foreach ($desarrollosExtra as $dev) {
            $ultimaVersion = $versionesExtra[$dev->id];
            if ($ultimaVersion) {
                $adjExtra = Adjunto::create([
                    'id_tipo'       => $faker->randomElement([$tipoBinario->id, $tipoDicc->id, $tipoMenu->id]),
                    'arbol_carpeta' => strtolower(str_replace(' ', '_', $dev->nombre)) . '/bin/main.exe',
                ]);
                $avExtra = AdjuntoVersion::create([
                    					'id_adj'    => $adjExtra->id,
                    'version_major' => $ultimaVersion->version_major,
                    'version_minor' => $ultimaVersion->version_minor,
                    'version_patch' => $ultimaVersion->version_patch,
                    'hash'          => md5($dev->nombre . 'v'),
                    'fecha'         => $faker->dateTimeBetween('-6 months', 'now'),
                ]);
                DesarrolloAdjunto::create([
                    'id_dev_version'     => $ultimaVersion->id,
                    'id_adj_version' => $avExtra->id,
                    'obligatorio'        => true,
                    'orden'              => 1,
                ]);
            }
        }

        // ====================================================
        // 5. VERSIONES VIGENTES
        // ====================================================
        DesarrolloActual::create(['id_dev' => $devFito->id,        'id_dev_version' => $fitoV3->id]);
        DesarrolloActual::create(['id_dev' => $devTraza->id,       'id_dev_version' => $trazaV2->id]);
        DesarrolloActual::create(['id_dev' => $devLogistica->id,   'id_dev_version' => $logV2->id]);
        DesarrolloActual::create(['id_dev' => $devReportes->id,    'id_dev_version' => $repV2->id]);
        DesarrolloActual::create(['id_dev' => $devContab->id,      'id_dev_version' => $contabV2->id]);
        DesarrolloActual::create(['id_dev' => $devFactura->id,     'id_dev_version' => $factV1->id]);
        DesarrolloActual::create(['id_dev' => $devRRHH->id,        'id_dev_version' => $rrhhV2->id]);
        DesarrolloActual::create(['id_dev' => $devCRM->id,         'id_dev_version' => $crmV1->id]);
        DesarrolloActual::create(['id_dev' => $devTPV->id,         'id_dev_version' => $tpvV1->id]);
        DesarrolloActual::create(['id_dev' => $devEcom->id,        'id_dev_version' => $ecomV1->id]);
        DesarrolloActual::create(['id_dev' => $devBI->id,          'id_dev_version' => $biV1->id]);
        DesarrolloActual::create(['id_dev' => $devQR->id,          'id_dev_version' => $qrV1->id]);
        DesarrolloActual::create(['id_dev' => $devAlmacen->id,     'id_dev_version' => $almacenV1->id]);
        DesarrolloActual::create(['id_dev' => $devCalidad->id,     'id_dev_version' => $calidadV1->id]);
        DesarrolloActual::create(['id_dev' => $devMante->id,       'id_dev_version' => $manteV1->id]);
        DesarrolloActual::create(['id_dev' => $devObras->id,       'id_dev_version' => $obrasV1->id]);
        DesarrolloActual::create(['id_dev' => $devTransporte->id,  'id_dev_version' => $transpV1->id]);
        DesarrolloActual::create(['id_dev' => $devDocGest->id,     'id_dev_version' => $docgestV1->id]);
        DesarrolloActual::create(['id_dev' => $devProd->id,        'id_dev_version' => $prodV1->id]);
        DesarrolloActual::create(['id_dev' => $devLegado->id,      'id_dev_version' => $legadoV1->id]);

        foreach ($desarrollosExtra as $dev) {
            $ultimaVersion = $versionesExtra[$dev->id];
            if ($ultimaVersion) {
                DesarrolloActual::create(['id_dev' => $dev->id, 'id_dev_version' => $ultimaVersion->id]);
            }
        }

        // ====================================================
        // 6. CLIENTES (100 clientes: 25 controlados + 72 Faker + 3 inactivos controlados)
        // ====================================================
        $clientesData = [
            ['nombre' => 'Bodegas Riojanas SA',         'codigo' => 'CLI-001', 'version' => '12.5.0', 'activo' => true],
            ['nombre' => 'Distribuciones Norte',         'codigo' => 'CLI-002', 'version' => '12.4.0', 'activo' => true],
            ['nombre' => 'Frutas del Ebro',              'codigo' => 'CLI-003', 'version' => '12.5.0', 'activo' => true],
            ['nombre' => 'Logística Pirineos',           'codigo' => 'CLI-004', 'version' => '12.3.0', 'activo' => true],
            ['nombre' => 'Agrícola Navarra SL',          'codigo' => 'CLI-006', 'version' => '12.5.0', 'activo' => true],
            ['nombre' => 'Transportes Ebro SA',          'codigo' => 'CLI-007', 'version' => '12.4.0', 'activo' => true],
            ['nombre' => 'Conservas del Norte SL',       'codigo' => 'CLI-008', 'version' => '12.5.0', 'activo' => true],
            ['nombre' => 'Almacenes Rioja SL',           'codigo' => 'CLI-009', 'version' => '12.3.0', 'activo' => true],
            ['nombre' => 'Grupo Alimentario Aragón',     'codigo' => 'CLI-010', 'version' => '12.5.0', 'activo' => true],
            ['nombre' => 'Cooperativa La Rioja',         'codigo' => 'CLI-011', 'version' => '12.4.0', 'activo' => true],
            ['nombre' => 'Viveros del Ebro SA',          'codigo' => 'CLI-012', 'version' => '12.5.0', 'activo' => true],
            ['nombre' => 'Bodegas Olarra SL',            'codigo' => 'CLI-013', 'version' => '12.3.0', 'activo' => true],
            ['nombre' => 'Lácteos Navarros SL',          'codigo' => 'CLI-014', 'version' => '12.5.0', 'activo' => true],
            ['nombre' => 'Cárnicas Aragón SA',           'codigo' => 'CLI-015', 'version' => '12.4.0', 'activo' => true],
            ['nombre' => 'Fitosanitarios del Ebro SL',   'codigo' => 'CLI-016', 'version' => '12.5.0', 'activo' => true],
            ['nombre' => 'Grupo Hortofrutícola Norte',   'codigo' => 'CLI-017', 'version' => '12.3.0', 'activo' => true],
            ['nombre' => 'Distribuciones Camino SL',     'codigo' => 'CLI-018', 'version' => '12.5.0', 'activo' => true],
            ['nombre' => 'Bodegas Marqués de Riscal SA', 'codigo' => 'CLI-019', 'version' => '12.4.0', 'activo' => true],
            ['nombre' => 'Frutas y Verduras Aragón SL',  'codigo' => 'CLI-020', 'version' => '12.5.0', 'activo' => true],
            ['nombre' => 'Almacenes del Ebro SA',        'codigo' => 'CLI-021', 'version' => '12.3.0', 'activo' => true],
            ['nombre' => 'Cerámica Riojana SL',          'codigo' => 'CLI-022', 'version' => '12.5.0', 'activo' => true],
            ['nombre' => 'Construcciones Norte SA',      'codigo' => 'CLI-023', 'version' => '12.4.0', 'activo' => true],
            ['nombre' => 'Talleres Mecánicos Ebro',      'codigo' => 'CLI-024', 'version' => '12.5.0', 'activo' => true],
            ['nombre' => 'Panaderías Rioja SL',          'codigo' => 'CLI-025', 'version' => '12.3.0', 'activo' => true],
            ['nombre' => 'Automoción Navarra SA',        'codigo' => 'CLI-026', 'version' => '12.5.0', 'activo' => true],
            ['nombre' => 'Comercial Antigua SL',         'codigo' => 'CLI-005', 'version' => '11.0.0', 'activo' => false],
            ['nombre' => 'Industrias Cerradas SA',       'codigo' => 'CLI-027', 'version' => '11.5.0', 'activo' => false],
            ['nombre' => 'Distribuciones Quebradas SL',  'codigo' => 'CLI-028', 'version' => '12.0.0', 'activo' => false],
        ];

        $clientes = [];
        foreach ($clientesData as $data) {
            $clientes[] = Cliente::create([
                'nombre'        => $data['nombre'],
                'codigo'        => $data['codigo'],
                'version_a3erp' => $data['version'],
                'activo'        => $data['activo'],
            ]);
        }

        // 72 clientes adicionales con Faker
        $versionesA3 = ['11.0.0', '12.0.0', '12.1.0', '12.2.0', '12.3.0', '12.4.0', '12.5.0'];
        for ($i = 29; $i <= 100; $i++) {
            $clientes[] = Cliente::create([
                'nombre'        => $faker->company,
                'codigo'        => 'CLI-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'version_a3erp' => $faker->randomElement($versionesA3),
                'activo'        => $faker->boolean(85),
            ]);
        }

        [$c1,$c2,$c3,$c4,$c5,$c6,$c7,$c8,$c9,$c10,
         $c11,$c12,$c13,$c14,$c15,$c16,$c17,$c18,$c19,$c20,
         $c21,$c22,$c23,$c24,$c25] = array_slice($clientes, 0, 25);

        // ====================================================
        // 7. INSTANCIAS
        // ====================================================
        $instancias = [];

        // C1: 4 instancias
        $instancias[] = $i1a = ClienteInstancia::create(['id_cliente' => $c1->id, 'nombre' => 'PC-CONTABILIDAD', 'host' => 'PC-CONT-01', 'ip' => '192.168.1.10', 'ultima_conexion' => Carbon::now()->subHours(2)]);
        $instancias[] = $i1b = ClienteInstancia::create(['id_cliente' => $c1->id, 'nombre' => 'PC-ALMACEN',      'host' => 'PC-ALM-01',  'ip' => '192.168.1.11', 'ultima_conexion' => Carbon::now()->subHours(5)]);
        $instancias[] = $i1c = ClienteInstancia::create(['id_cliente' => $c1->id, 'nombre' => 'PC-DIRECCION',    'host' => 'PC-DIR-01',  'ip' => '192.168.1.12', 'ultima_conexion' => Carbon::now()->subDay()]);
        $instancias[] = $i1d = ClienteInstancia::create(['id_cliente' => $c1->id, 'nombre' => 'PC-VENTAS',       'host' => 'PC-VEN-01',  'ip' => '192.168.1.13', 'ultima_conexion' => Carbon::now()->subHours(1)]);

        // C2: 3 instancias
        $instancias[] = $i2a = ClienteInstancia::create(['id_cliente' => $c2->id, 'nombre' => 'PC-OFICINA',       'host' => 'PC-OFI-01', 'ip' => '10.0.0.20', 'ultima_conexion' => Carbon::now()->subHours(3)]);
        $instancias[] = $i2b = ClienteInstancia::create(['id_cliente' => $c2->id, 'nombre' => 'PC-ALMACEN-NORTE', 'host' => 'PC-ALM-02', 'ip' => '10.0.0.21', 'ultima_conexion' => Carbon::now()->subDays(45)]);
        $instancias[] = $i2c = ClienteInstancia::create(['id_cliente' => $c2->id, 'nombre' => 'PC-LOGISTICA',     'host' => 'PC-LOG-01', 'ip' => '10.0.0.22', 'ultima_conexion' => Carbon::now()->subDays(2)]);

        // C3: 3 instancias
        $instancias[] = $i3a = ClienteInstancia::create(['id_cliente' => $c3->id, 'nombre' => 'PC-PRINCIPAL',  'host' => 'PC-PRIN-01', 'ip' => '172.16.0.5', 'ultima_conexion' => Carbon::now()->subHours(1)]);
        $instancias[] = $i3b = ClienteInstancia::create(['id_cliente' => $c3->id, 'nombre' => 'PC-SECUNDARIO', 'host' => 'PC-SEC-01',  'ip' => '172.16.0.6', 'ultima_conexion' => Carbon::now()->subDays(3)]);
        $instancias[] = $i3c = ClienteInstancia::create(['id_cliente' => $c3->id, 'nombre' => 'PC-GERENCIA',   'host' => 'PC-GER-01',  'ip' => '172.16.0.7', 'ultima_conexion' => Carbon::now()->subHours(6)]);

        // C4: 3 instancias
        $instancias[] = $i4a = ClienteInstancia::create(['id_cliente' => $c4->id, 'nombre' => 'SERVIDOR-PIR', 'host' => 'SRV-PIR-01', 'ip' => '192.168.50.1',  'ultima_conexion' => Carbon::now()->subDays(2)]);
        $instancias[] = $i4b = ClienteInstancia::create(['id_cliente' => $c4->id, 'nombre' => 'PC-RECEPCION', 'host' => 'PC-REC-01',  'ip' => '192.168.50.10', 'ultima_conexion' => Carbon::now()->subDays(2)]);
        $instancias[] = $i4c = ClienteInstancia::create(['id_cliente' => $c4->id, 'nombre' => 'PC-ALMACEN',   'host' => 'PC-ALM-04',  'ip' => '192.168.50.11', 'ultima_conexion' => Carbon::now()->subDays(1)]);

        // C5-C10: 3 instancias cada uno
        foreach ([$c5,$c6,$c7,$c8,$c9,$c10] as $idx => $cliente) {
            $instancias[] = ClienteInstancia::create(['id_cliente' => $cliente->id, 'nombre' => 'SERVIDOR',   'host' => 'SRV-' . str_pad($idx+5,2,'0',STR_PAD_LEFT), 'ip' => '10.10.'.($idx+1).'.1',  'ultima_conexion' => Carbon::now()->subHours(rand(1,48))]);
            $instancias[] = ClienteInstancia::create(['id_cliente' => $cliente->id, 'nombre' => 'PC-OFICINA', 'host' => 'PC-' . str_pad($idx+5,2,'0',STR_PAD_LEFT),  'ip' => '10.10.'.($idx+1).'.10', 'ultima_conexion' => Carbon::now()->subHours(rand(1,72))]);
            $instancias[] = ClienteInstancia::create(['id_cliente' => $cliente->id, 'nombre' => 'PC-ALMACEN', 'host' => 'PC-ALM-'.str_pad($idx+5,2,'0',STR_PAD_LEFT),'ip' => '10.10.'.($idx+1).'.11', 'ultima_conexion' => Carbon::now()->subHours(rand(1,96))]);
        }

        // C11-C25: 2 instancias cada uno
        foreach ([$c11,$c12,$c13,$c14,$c15,$c16,$c17,$c18,$c19,$c20,$c21,$c22,$c23,$c24,$c25] as $idx => $cliente) {
            $instancias[] = ClienteInstancia::create(['id_cliente' => $cliente->id, 'nombre' => 'PC-PRINCIPAL',   'host' => 'PC-'.str_pad($idx+11,2,'0',STR_PAD_LEFT),     'ip' => '192.168.'.($idx+10).'.10', 'ultima_conexion' => Carbon::now()->subHours(rand(1,120))]);
            $instancias[] = ClienteInstancia::create(['id_cliente' => $cliente->id, 'nombre' => 'PC-SECUNDARIO',  'host' => 'PC-SEC-'.str_pad($idx+11,2,'0',STR_PAD_LEFT), 'ip' => '192.168.'.($idx+10).'.11', 'ultima_conexion' => Carbon::now()->subHours(rand(1,120))]);
        }

        // Clientes Faker (índices 28-99): entre 1 y 5 instancias cada uno
        for ($i = 28; $i < count($clientes); $i++) {
            $numInst = $faker->numberBetween(1, 5);
            for ($j = 1; $j <= $numInst; $j++) {
                $instancias[] = ClienteInstancia::create([
                    'id_cliente'     => $clientes[$i]->id,
                    'nombre'         => $j === 1 ? 'SERVIDOR' : 'PC-' . strtoupper($faker->lexify('???')),
                    'host'           => strtoupper($faker->bothify('HOST-????-##')),
                    'ip'             => $faker->localIpv4,
                    'ultima_conexion'=> $faker->dateTimeBetween('-3 months', 'now'),
                ]);
            }
        }

        // ====================================================
        // 8. LICENCIAS
        // ====================================================
        $l1a = ClienteDesarrollo::create(['id_cliente' => $c1->id, 'id_dev' => $devFito->id,     'activo' => true]);
        $l1b = ClienteDesarrollo::create(['id_cliente' => $c1->id, 'id_dev' => $devTraza->id,    'activo' => true]);
        $l1c = ClienteDesarrollo::create(['id_cliente' => $c1->id, 'id_dev' => $devReportes->id, 'activo' => true]);
        $l1d = ClienteDesarrollo::create(['id_cliente' => $c1->id, 'id_dev' => $devContab->id,   'activo' => true]);

        $l2a = ClienteDesarrollo::create(['id_cliente' => $c2->id, 'id_dev' => $devLogistica->id,'activo' => true]);
        $l2b = ClienteDesarrollo::create(['id_cliente' => $c2->id, 'id_dev' => $devTraza->id,    'activo' => true]);
        $l2c = ClienteDesarrollo::create(['id_cliente' => $c2->id, 'id_dev' => $devFactura->id,  'activo' => true]);

        $l3a = ClienteDesarrollo::create(['id_cliente' => $c3->id, 'id_dev' => $devFito->id,     'activo' => true]);
        $l3b = ClienteDesarrollo::create(['id_cliente' => $c3->id, 'id_dev' => $devReportes->id, 'activo' => true]);

        $l4a = ClienteDesarrollo::create(['id_cliente' => $c4->id, 'id_dev' => $devLogistica->id,'activo' => true]);
        $l4b = ClienteDesarrollo::create(['id_cliente' => $c4->id, 'id_dev' => $devReportes->id, 'activo' => true]);
        $l4c = ClienteDesarrollo::create(['id_cliente' => $c4->id, 'id_dev' => $devRRHH->id,     'activo' => true]);

        $l5a = ClienteDesarrollo::create(['id_cliente' => $c5->id, 'id_dev' => $devFito->id,     'activo' => true]);
        $l5b = ClienteDesarrollo::create(['id_cliente' => $c5->id, 'id_dev' => $devCRM->id,      'activo' => true]);
        $l5c = ClienteDesarrollo::create(['id_cliente' => $c5->id, 'id_dev' => $devTPV->id,      'activo' => true]);

        $l6a = ClienteDesarrollo::create(['id_cliente' => $c6->id, 'id_dev' => $devLogistica->id,  'activo' => true]);
        $l6b = ClienteDesarrollo::create(['id_cliente' => $c6->id, 'id_dev' => $devTransporte->id, 'activo' => true]);

        $l7a = ClienteDesarrollo::create(['id_cliente' => $c7->id, 'id_dev' => $devTraza->id,    'activo' => true]);
        $l7b = ClienteDesarrollo::create(['id_cliente' => $c7->id, 'id_dev' => $devAlmacen->id,  'activo' => true]);
        $l7c = ClienteDesarrollo::create(['id_cliente' => $c7->id, 'id_dev' => $devCalidad->id,  'activo' => true]);

        $l8a = ClienteDesarrollo::create(['id_cliente' => $c8->id, 'id_dev' => $devFito->id,     'activo' => true]);
        $l8b = ClienteDesarrollo::create(['id_cliente' => $c8->id, 'id_dev' => $devTraza->id,    'activo' => true]);

        $l9a = ClienteDesarrollo::create(['id_cliente' => $c9->id, 'id_dev' => $devBI->id,       'activo' => true]);
        $l9b = ClienteDesarrollo::create(['id_cliente' => $c9->id, 'id_dev' => $devReportes->id, 'activo' => true]);
        $l9c = ClienteDesarrollo::create(['id_cliente' => $c9->id, 'id_dev' => $devDocGest->id,  'activo' => true]);

        $l10a = ClienteDesarrollo::create(['id_cliente' => $c10->id, 'id_dev' => $devProd->id,   'activo' => true]);
        $l10b = ClienteDesarrollo::create(['id_cliente' => $c10->id, 'id_dev' => $devMante->id,  'activo' => true]);

        $devsSimples = [$devFito,$devTraza,$devLogistica,$devReportes,$devContab,
                        $devFactura,$devRRHH,$devCRM,$devTPV,$devEcom,
                        $devBI,$devQR,$devAlmacen,$devCalidad,$devMante];
        $licsSimples = [];
        foreach ([$c11,$c12,$c13,$c14,$c15,$c16,$c17,$c18,$c19,$c20,$c21,$c22,$c23,$c24,$c25] as $idx => $cliente) {
            $licsSimples[$idx] = ClienteDesarrollo::create(['id_cliente' => $cliente->id, 'id_dev' => $devsSimples[$idx]->id, 'activo' => true]);
        }

        // Licencias para clientes Faker
        $licsFaker = [];
        $devsActivos = array_filter($todosDesarrollos, fn($d) => $d->activo);
        $devsActivos = array_values($devsActivos);
        for ($i = 28; $i < count($clientes); $i++) {
            $numLics = $faker->numberBetween(1, 5);
            $devsAsignados = $faker->randomElements($devsActivos, min($numLics, count($devsActivos)));
            $licsFaker[$i] = [];
            foreach ($devsAsignados as $dev) {
                $licsFaker[$i][] = ClienteDesarrollo::create([
                    'id_cliente' => $clientes[$i]->id,
                    'id_dev'     => $dev->id,
                    'activo'     => true,
                ]);
            }
        }

        // ====================================================
        // 9. ESTADO ACTUAL DE LICENCIAS
        // ====================================================
        ClienteDesarrolloActual::create(['id_cliente_desarrollo' => $l1a->id, 'id_dev_version' => $fitoV3->id]);
        ClienteDesarrolloActual::create(['id_cliente_desarrollo' => $l1b->id, 'id_dev_version' => $trazaV2->id]);
        ClienteDesarrolloActual::create(['id_cliente_desarrollo' => $l1c->id, 'id_dev_version' => $repV2->id]);
        ClienteDesarrolloActual::create(['id_cliente_desarrollo' => $l1d->id, 'id_dev_version' => $contabV2->id]);

        ClienteDesarrolloActual::create(['id_cliente_desarrollo' => $l2a->id, 'id_dev_version' => $logV1->id]);
        ClienteDesarrolloActual::create(['id_cliente_desarrollo' => $l2b->id, 'id_dev_version' => $trazaV2->id]);
        ClienteDesarrolloActual::create(['id_cliente_desarrollo' => $l2c->id, 'id_dev_version' => $factV1->id]);

        ClienteDesarrolloActual::create(['id_cliente_desarrollo' => $l3a->id, 'id_dev_version' => $fitoV3->id]);
        ClienteDesarrolloActual::create(['id_cliente_desarrollo' => $l3b->id, 'id_dev_version' => $repV2->id]);

        ClienteDesarrolloActual::create(['id_cliente_desarrollo' => $l4a->id, 'id_dev_version' => $logV1->id]);
        ClienteDesarrolloActual::create(['id_cliente_desarrollo' => $l4b->id, 'id_dev_version' => $repV1->id]);
        ClienteDesarrolloActual::create(['id_cliente_desarrollo' => $l4c->id, 'id_dev_version' => $rrhhV1->id]);

        ClienteDesarrolloActual::create(['id_cliente_desarrollo' => $l5a->id, 'id_dev_version' => $fitoV3->id]);
        ClienteDesarrolloActual::create(['id_cliente_desarrollo' => $l5b->id, 'id_dev_version' => $crmV1->id]);
        ClienteDesarrolloActual::create(['id_cliente_desarrollo' => $l5c->id, 'id_dev_version' => $tpvV1->id]);

        ClienteDesarrolloActual::create(['id_cliente_desarrollo' => $l6a->id, 'id_dev_version' => $logV1->id]);
        ClienteDesarrolloActual::create(['id_cliente_desarrollo' => $l6b->id, 'id_dev_version' => $transpV1->id]);

        ClienteDesarrolloActual::create(['id_cliente_desarrollo' => $l7a->id, 'id_dev_version' => $trazaV2->id]);
        ClienteDesarrolloActual::create(['id_cliente_desarrollo' => $l7b->id, 'id_dev_version' => $almacenV1->id]);
        ClienteDesarrolloActual::create(['id_cliente_desarrollo' => $l7c->id, 'id_dev_version' => $calidadV1->id]);

        ClienteDesarrolloActual::create(['id_cliente_desarrollo' => $l8a->id, 'id_dev_version' => $fitoV2->id]);
        ClienteDesarrolloActual::create(['id_cliente_desarrollo' => $l8b->id, 'id_dev_version' => $trazaV1->id]);

        ClienteDesarrolloActual::create(['id_cliente_desarrollo' => $l9a->id, 'id_dev_version' => $biV1->id]);
        ClienteDesarrolloActual::create(['id_cliente_desarrollo' => $l9b->id, 'id_dev_version' => $repV2->id]);
        ClienteDesarrolloActual::create(['id_cliente_desarrollo' => $l9c->id, 'id_dev_version' => $docgestV1->id]);

        ClienteDesarrolloActual::create(['id_cliente_desarrollo' => $l10a->id, 'id_dev_version' => $prodV1->id]);
        ClienteDesarrolloActual::create(['id_cliente_desarrollo' => $l10b->id, 'id_dev_version' => $manteV1->id]);

        $versionesSimples = [$fitoV3,$trazaV2,$logV2,$repV2,$contabV2,
                             $factV1,$rrhhV2,$crmV1,$tpvV1,$ecomV1,
                             $biV1,$qrV1,$almacenV1,$calidadV1,$manteV1];
        foreach ($licsSimples as $idx => $lic) {
            ClienteDesarrolloActual::create(['id_cliente_desarrollo' => $lic->id, 'id_dev_version' => $versionesSimples[$idx]->id]);
        }

        // Estado actual para licencias Faker
        for ($i = 28; $i < count($clientes); $i++) {
            foreach ($licsFaker[$i] as $lic) {
                $dev = Desarrollo::find($lic->id_dev);
                $actual = DesarrolloActual::where('id_dev', $dev->id)->first();
                if ($actual) {
                    $todasVersiones = DesarrolloVersion::where('id_dev', $dev->id)->get();
                    $versionInstalada = $faker->boolean(70)
                        ? $actual->version
                        : $faker->randomElement($todasVersiones);
                    ClienteDesarrolloActual::create([
                        'id_cliente_desarrollo' => $lic->id,
                        'id_dev_version'        => $versionInstalada->id,
                    ]);
                }
            }
        }

        // ====================================================
        // 10. HISTÓRICO DE INSTALACIONES (~1150 registros)
        // ====================================================

        // Controlados (mismos que antes)
        ClienteInstanciaVersion::create(['id_instancia' => $i1a->id, 'id_cliente_desarrollo' => $l1a->id, 'id_dev_version' => $fitoV1->id, 'fecha_actualizacion' => Carbon::now()->subDays(90), 'resultado' => 'ok',    'observaciones' => 'Instalación inicial']);
        ClienteInstanciaVersion::create(['id_instancia' => $i1a->id, 'id_cliente_desarrollo' => $l1a->id, 'id_dev_version' => $fitoV2->id, 'fecha_actualizacion' => Carbon::now()->subDays(60), 'resultado' => 'ok',    'observaciones' => 'Actualización a v1.1.0']);
        ClienteInstanciaVersion::create(['id_instancia' => $i1a->id, 'id_cliente_desarrollo' => $l1a->id, 'id_dev_version' => $fitoV3->id, 'fecha_actualizacion' => Carbon::now()->subDays(5),  'resultado' => 'ok',    'observaciones' => 'Actualización a v1.2.0']);
        ClienteInstanciaVersion::create(['id_instancia' => $i1b->id, 'id_cliente_desarrollo' => $l1b->id, 'id_dev_version' => $trazaV1->id,'fecha_actualizacion' => Carbon::now()->subDays(45), 'resultado' => 'ok',    'observaciones' => 'Instalación inicial']);
        ClienteInstanciaVersion::create(['id_instancia' => $i1b->id, 'id_cliente_desarrollo' => $l1b->id, 'id_dev_version' => $trazaV2->id,'fecha_actualizacion' => Carbon::now()->subDays(10), 'resultado' => 'ok',    'observaciones' => 'Actualización a v1.1.0']);
        ClienteInstanciaVersion::create(['id_instancia' => $i1c->id, 'id_cliente_desarrollo' => $l1c->id, 'id_dev_version' => $repV1->id,  'fecha_actualizacion' => Carbon::now()->subDays(30), 'resultado' => 'ok',    'observaciones' => 'Instalación inicial']);
        ClienteInstanciaVersion::create(['id_instancia' => $i1c->id, 'id_cliente_desarrollo' => $l1c->id, 'id_dev_version' => $repV2->id,  'fecha_actualizacion' => Carbon::now()->subDays(8),  'resultado' => 'ok',    'observaciones' => 'Actualización a v3.1.0']);
        ClienteInstanciaVersion::create(['id_instancia' => $i1c->id, 'id_cliente_desarrollo' => $l1d->id, 'id_dev_version' => $contabV1->id,'fecha_actualizacion'=> Carbon::now()->subDays(20), 'resultado' => 'ok',    'observaciones' => 'Instalación inicial']);
        ClienteInstanciaVersion::create(['id_instancia' => $i1c->id, 'id_cliente_desarrollo' => $l1d->id, 'id_dev_version' => $contabV2->id,'fecha_actualizacion'=> Carbon::now()->subDays(3),  'resultado' => 'ok',    'observaciones' => 'Actualización a v1.1.0']);
        ClienteInstanciaVersion::create(['id_instancia' => $i1d->id, 'id_cliente_desarrollo' => $l1a->id, 'id_dev_version' => $fitoV3->id, 'fecha_actualizacion' => Carbon::now()->subDays(4),  'resultado' => 'ok',    'observaciones' => 'Instalación en PC Ventas']);

        ClienteInstanciaVersion::create(['id_instancia' => $i2a->id, 'id_cliente_desarrollo' => $l2a->id, 'id_dev_version' => $logV1->id,  'fecha_actualizacion' => Carbon::now()->subDays(120),'resultado' => 'ok',    'observaciones' => 'Instalación inicial Logística']);
        ClienteInstanciaVersion::create(['id_instancia' => $i2a->id, 'id_cliente_desarrollo' => $l2b->id, 'id_dev_version' => $trazaV1->id,'fecha_actualizacion' => Carbon::now()->subDays(80), 'resultado' => 'ok',    'observaciones' => 'Instalación inicial']);
        ClienteInstanciaVersion::create(['id_instancia' => $i2a->id, 'id_cliente_desarrollo' => $l2b->id, 'id_dev_version' => $trazaV2->id,'fecha_actualizacion' => Carbon::now()->subDays(15), 'resultado' => 'ok',    'observaciones' => 'Actualización a v1.1.0']);
        ClienteInstanciaVersion::create(['id_instancia' => $i2b->id, 'id_cliente_desarrollo' => $l2c->id, 'id_dev_version' => $factV1->id, 'fecha_actualizacion' => Carbon::now()->subDays(50), 'resultado' => 'ok',    'observaciones' => 'Instalación facturación']);
        ClienteInstanciaVersion::create(['id_instancia' => $i2c->id, 'id_cliente_desarrollo' => $l2a->id, 'id_dev_version' => $logV1->id,  'fecha_actualizacion' => Carbon::now()->subDays(110),'resultado' => 'ok',    'observaciones' => 'Instalación logística PC logística']);

        ClienteInstanciaVersion::create(['id_instancia' => $i3a->id, 'id_cliente_desarrollo' => $l3a->id, 'id_dev_version' => $fitoV1->id, 'fecha_actualizacion' => Carbon::now()->subDays(60), 'resultado' => 'ok',    'observaciones' => 'Instalación inicial']);
        ClienteInstanciaVersion::create(['id_instancia' => $i3a->id, 'id_cliente_desarrollo' => $l3a->id, 'id_dev_version' => $fitoV2->id, 'fecha_actualizacion' => Carbon::now()->subDays(30), 'resultado' => 'ok',    'observaciones' => 'Actualización a v1.1.0']);
        ClienteInstanciaVersion::create(['id_instancia' => $i3a->id, 'id_cliente_desarrollo' => $l3a->id, 'id_dev_version' => $fitoV3->id, 'fecha_actualizacion' => Carbon::now()->subDays(2),  'resultado' => 'error', 'observaciones' => 'Fallo: dependencia no encontrada']);
        ClienteInstanciaVersion::create(['id_instancia' => $i3b->id, 'id_cliente_desarrollo' => $l3b->id, 'id_dev_version' => $repV1->id,  'fecha_actualizacion' => Carbon::now()->subDays(25), 'resultado' => 'ok',    'observaciones' => 'Instalación reportes']);
        ClienteInstanciaVersion::create(['id_instancia' => $i3b->id, 'id_cliente_desarrollo' => $l3b->id, 'id_dev_version' => $repV2->id,  'fecha_actualizacion' => Carbon::now()->subDays(4),  'resultado' => 'error', 'observaciones' => 'Fallo: error de permisos en directorio']);
        ClienteInstanciaVersion::create(['id_instancia' => $i3c->id, 'id_cliente_desarrollo' => $l3a->id, 'id_dev_version' => $fitoV2->id, 'fecha_actualizacion' => Carbon::now()->subDays(28), 'resultado' => 'ok',    'observaciones' => 'Instalación en PC gerencia']);

        ClienteInstanciaVersion::create(['id_instancia' => $i4a->id, 'id_cliente_desarrollo' => $l4a->id, 'id_dev_version' => $logV1->id,  'fecha_actualizacion' => Carbon::now()->subDays(100),'resultado' => 'ok',    'observaciones' => 'Instalación inicial Logística']);
        ClienteInstanciaVersion::create(['id_instancia' => $i4a->id, 'id_cliente_desarrollo' => $l4b->id, 'id_dev_version' => $repV1->id,  'fecha_actualizacion' => Carbon::now()->subDays(70), 'resultado' => 'ok',    'observaciones' => 'Instalación reportes']);
        ClienteInstanciaVersion::create(['id_instancia' => $i4b->id, 'id_cliente_desarrollo' => $l4c->id, 'id_dev_version' => $rrhhV1->id, 'fecha_actualizacion' => Carbon::now()->subDays(40), 'resultado' => 'ok',    'observaciones' => 'Instalación RRHH']);
        ClienteInstanciaVersion::create(['id_instancia' => $i4c->id, 'id_cliente_desarrollo' => $l4a->id, 'id_dev_version' => $logV1->id,  'fecha_actualizacion' => Carbon::now()->subDays(95), 'resultado' => 'ok',    'observaciones' => 'Instalación logística almacén']);

        // Instancias C5-C10 (índices en array $instancias)
        $baseIdx = 13; // tras c1(4) + c2(3) + c3(3) + c4(3) = 13
        foreach ([$l5a,$l5b,$l5c] as $lic) {
            $inst = $instancias[$baseIdx];
            ClienteInstanciaVersion::create(['id_instancia' => $inst->id, 'id_cliente_desarrollo' => $lic->id, 'id_dev_version' => $faker->randomElement([$fitoV3->id,$crmV1->id,$tpvV1->id]), 'fecha_actualizacion' => Carbon::now()->subDays(rand(1,15)), 'resultado' => 'ok', 'observaciones' => 'Instalación']);
        }

        $instC6base = $instancias[16];
        ClienteInstanciaVersion::create(['id_instancia' => $instC6base->id, 'id_cliente_desarrollo' => $l6a->id, 'id_dev_version' => $logV1->id,   'fecha_actualizacion' => Carbon::now()->subDays(90), 'resultado' => 'ok',    'observaciones' => 'Instalación inicial']);
        ClienteInstanciaVersion::create(['id_instancia' => $instC6base->id, 'id_cliente_desarrollo' => $l6b->id, 'id_dev_version' => $transpV1->id, 'fecha_actualizacion' => Carbon::now()->subDays(3),  'resultado' => 'error', 'observaciones' => 'Fallo: timeout en conexión']);

        // C11-C25: 2 registros por licencia
        $versionesSimples2 = [$fitoV3,$trazaV2,$logV2,$repV2,$contabV2,
                              $factV1,$rrhhV2,$crmV1,$tpvV1,$ecomV1,
                              $biV1,$qrV1,$almacenV1,$calidadV1,$manteV1];
        $instOffset = 31; // tras c1-c10
        foreach ($licsSimples as $idx => $lic) {
            $inst = $instancias[$instOffset + ($idx * 2)];
            ClienteInstanciaVersion::create(['id_instancia' => $inst->id, 'id_cliente_desarrollo' => $lic->id, 'id_dev_version' => $versionesSimples2[$idx]->id, 'fecha_actualizacion' => Carbon::now()->subDays(rand(5,60)), 'resultado' => 'ok', 'observaciones' => 'Instalación correcta']);
            ClienteInstanciaVersion::create(['id_instancia' => $inst->id, 'id_cliente_desarrollo' => $lic->id, 'id_dev_version' => $versionesSimples2[$idx]->id, 'fecha_actualizacion' => Carbon::now()->subDays(rand(61,120)), 'resultado' => 'ok', 'observaciones' => 'Instalación inicial']);
        }

        // Histórico masivo para clientes Faker (~1000 registros adicionales)
        $instanciasFaker = array_filter($instancias, function($inst) use ($clientes) {
            foreach (array_slice($clientes, 28) as $c) {
                if ($inst->id_cliente === $c->id) return true;
            }
            return false;
        });
        $instanciasFaker = array_values($instanciasFaker);

        $registrosGenerados = 0;
        $objetivo = 1000;

        while ($registrosGenerados < $objetivo) {
            $inst = $faker->randomElement($instanciasFaker);
            $clienteId = $inst->id_cliente;
            $clienteIdx = array_search($clientes[array_search($inst->id_cliente, array_column($clientes, 'id'))] ?? null, $clientes);

            $licsCliente = ClienteDesarrollo::where('id_cliente', $clienteId)->get();
            if ($licsCliente->isEmpty()) continue;

            $lic = $faker->randomElement($licsCliente->toArray());
            $versiones = DesarrolloVersion::where('id_dev', $lic['id_dev'])->get();
            if ($versiones->isEmpty()) continue;

            $version = $faker->randomElement($versiones->toArray());
            $esError = $faker->boolean(12);

            ClienteInstanciaVersion::create([
                'id_instancia'          => $inst->id,
                'id_cliente_desarrollo' => $lic['id'],
                'id_dev_version'        => $version['id'],
                'fecha_actualizacion'   => $faker->dateTimeBetween('-6 months', 'now'),
                'resultado'             => $esError ? 'error' : 'ok',
                'observaciones'         => $esError ? $faker->sentence(5) : 'Instalación completada correctamente',
            ]);

            $registrosGenerados++;
        }

        $this->command->info('Datos de prueba generados correctamente:');
        $this->command->info('- 100 clientes (97 activos, 3 inactivos)');
        $this->command->info('- 70 desarrollos (20 controlados + 50 adicionales)');
        $this->command->info('- Instancias: entre 1 y 5 por cliente');
        $this->command->info('- ~1150 registros en el histórico');
        $this->command->info('- Adjuntos vinculados a versiones controladas');
        $this->command->info('- Clientes desactualizados: C2, C4, C6, C8');
        $this->command->info('- Clientes con error reciente: C3, C6');
    }
}
