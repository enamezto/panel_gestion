<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

use App\Models\Cliente;
use App\Models\ClienteInstancia;
use App\Models\ClienteDesarrollo;
use App\Models\ClienteDesarrolloActual;
use App\Models\ClienteInstanciaVersion;

use App\Models\Desarrollo;
use App\Models\DesarrolloVersion;
use App\Models\DesarrolloActual;
use App\Models\AdjuntoTipo;

class DatosMasivosSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_ES');

        $this->command->info('Limpiando base de datos...');

        DB::statement('PRAGMA foreign_keys = OFF');
        ClienteInstanciaVersion::truncate();
        ClienteDesarrolloActual::truncate();
        ClienteDesarrollo::truncate();
        ClienteInstancia::truncate();
        Cliente::truncate();
        DesarrolloActual::truncate();
        DesarrolloVersion::truncate();
        Desarrollo::truncate();
        AdjuntoTipo::truncate();
        DB::statement('PRAGMA foreign_keys = ON');

        $this->command->info('Generando tipos de adjuntos...');
        AdjuntoTipo::create(['nombre' => 'Binario']);
        AdjuntoTipo::create(['nombre' => 'Diccionario']);
        AdjuntoTipo::create(['nombre' => 'Menu']);
        AdjuntoTipo::create(['nombre' => 'Imagen']);

        $this->command->info('Generando 15 Desarrollos y sus versiones...');
        $desarrollos = [];
        for ($i = 1; $i <= 15; $i++) {
            $dev = Desarrollo::create([
                'nombre'      => $faker->catchPhrase . ' a3ERP',
                'descripcion' => $faker->sentence(8),
                'tipo'        => $faker->randomElement(['Desarrollo', 'Librería', 'Proceso Externo']),
                'activo'      => $faker->boolean(90), // 90% activos
            ]);

            $numVersiones = $faker->numberBetween(1, 5);
            $ultimaVersion = null;

            for ($v = 1; $v <= $numVersiones; $v++) {
                $ultimaVersion = DesarrolloVersion::create([
                    'id_dev'              => $dev->id,
                    'version_major'       => $v,
                    'version_minor'       => $faker->numberBetween(0, 5),
                    'version_patch'       => $faker->numberBetween(0, 9),
                    'descripcion_cambios' => $faker->sentence(5),
                    'requiere_parada'     => $faker->boolean(30),
                    'version_a3erp_min'   => '11.0.0',
                    'fecha'               => $faker->dateTimeBetween('-2 years', 'now')
                ]);
            }

            // Guardar la última versión en la caché
            if ($ultimaVersion) {
                DesarrolloActual::create([
                    'id_dev'         => $dev->id,
                    'id_dev_version' => $ultimaVersion->id
                ]);
            }

            $desarrollos[] = $dev;
        }

        $this->command->info('Generando 50 Clientes y sus PCs...');
        $versionesA3 = ['11.0.0', '12.0.0', '12.5.0', '13.0.0', '13.5.0'];

        for ($i = 1; $i <= 50; $i++) {
            $cliente = Cliente::create([
                'nombre'        => $faker->company,
                'codigo'        => 'CLI-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'version_a3erp' => $faker->randomElement($versionesA3),
                'activo'        => $faker->boolean(85), // 85% activos
            ]);

            // Generar entre 1 y 6 instancias (PCs) por cliente
            $numInstancias = $faker->numberBetween(1, 6);
            $instancias = [];
            for ($j = 1; $j <= $numInstancias; $j++) {
                $instancias[] = ClienteInstancia::create([
                    'id_cliente'      => $cliente->id,
                    'nombre'          => $j === 1 ? 'SERVIDOR' : 'PC-' . strtoupper($faker->word),
                    'host'            => strtoupper($faker->bothify('HOST-????-##')),
                    'tipo'            => $j === 1 ? 'server' : 'cliente',
                    'ip'              => $faker->localIpv4,
                    'ultima_conexion' => $faker->dateTimeBetween('-1 month', 'now'),
                ]);
            }

            // Asignar entre 1 y 4 desarrollos aleatorios a este cliente
            $desarrollosAsignados = $faker->randomElements($desarrollos, $faker->numberBetween(1, 4));

            foreach ($desarrollosAsignados as $dev) {
                $licencia = ClienteDesarrollo::create([
                    'id_cliente' => $cliente->id,
                    'id_dev'     => $dev->id,
                    'activo'     => true,
                ]);

                // Elegir una versión al azar de este desarrollo para instalársela al cliente
                // Para que haya desactualizados, a veces no cogemos la última
                $todasLasVersiones = DesarrolloVersion::where('id_dev', $dev->id)->get();
                $versionInstalada = $faker->randomElement($todasLasVersiones);

                ClienteDesarrolloActual::create([
                    'id_cliente_desarrollo' => $licencia->id,
                    'id_dev_version'        => $versionInstalada->id,
                ]);

                // Generar historial de instalaciones para este desarrollo en los PCs del cliente
                foreach ($instancias as $instancia) {
                    // 1 a 3 instalaciones en el historial por PC y Desarrollo
                    $numInstalaciones = $faker->numberBetween(1, 3);

                    for ($k = 0; $k < $numInstalaciones; $k++) {
                        // 15% de probabilidad de error
                        $esError = $faker->boolean(15);

                        ClienteInstanciaVersion::create([
                            'id_instancia'          => $instancia->id,
                            'id_cliente_desarrollo' => $licencia->id,
                            'id_dev_version'        => $faker->randomElement($todasLasVersiones)->id,
                            'fecha_actualizacion'   => $faker->dateTimeBetween('-6 months', 'now'),
                            'resultado'             => $esError ? 'error' : 'ok',
                            'observaciones'         => $esError ? $faker->sentence(6) : 'Instalación completada correctamente',
                        ]);
                    }
                }
            }
        }

        $this->command->info('¡Datos masivos generados con éxito! Ahora tu panel luce espectacular.');
    }
}
