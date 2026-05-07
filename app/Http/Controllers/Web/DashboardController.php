<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Cliente;
use App\Models\ClienteInstancia;
use App\Models\ClienteInstanciaVersion;
use App\Models\ClienteDesarrolloActual;
use App\Models\DesarrolloActual;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Clientes activos
        $totalClientes = Cliente::where('activo', true)->count();

        // 2. Total de instancias (PCs)
        $totalInstancias = ClienteInstancia::count();

        // 3. Errores recientes (instalaciones fallidas en los últimos 7 días)
        $erroresRecientes = ClienteInstanciaVersion::where('resultado', '!=', 'ok')
            ->where('fecha_actualizacion', '>=', Carbon::now()->subDays(7))
            ->count();

        // 4. Clientes desactualizados (tienen al menos un módulo en versión antigua)
        $clientesDesactualizados = $this->contarClientesDesactualizados();

        // 5. Listado de clientes con su estado calculado
        $clientes = Cliente::where('activo', true)
            ->with('instancias')
            ->withCount('instancias')
            ->orderBy('nombre')
            ->get()
            ->map(function ($cliente) {
                return [
                    'id'              => $cliente->id,
                    'nombre'          => $cliente->nombre,
                    'version_a3erp'   => $cliente->version_a3erp,
                    'instancias'      => $cliente->instancias_count,
                    'ultima_conexion' => $cliente->instancias->max('ultima_conexion'),
                    'estado'          => $this->calcularEstadoCliente($cliente),
                ];
            });

        return view('dashboard.index', [
            'totalClientes'           => $totalClientes,
            'totalInstancias'         => $totalInstancias,
            'clientesDesactualizados' => $clientesDesactualizados,
            'erroresRecientes'        => $erroresRecientes,
            'clientes'                => $clientes,
        ]);
    }

    /**
     * Cuenta cuántos clientes activos tienen al menos un módulo desactualizado.
     */
    private function contarClientesDesactualizados(): int
    {
        // Obtenemos las versiones vigentes del catálogo (id_dev => id_dev_version actual)
        $versionesVigentes = DesarrolloActual::pluck('id_dev_version', 'id_dev');

        // Buscamos los clientes activos con sus licencias y la versión instalada
        $clientesActivos = Cliente::where('activo', true)
            ->with(['licencias.estadoActual', 'licencias.desarrollo'])
            ->get();

        $contador = 0;

        foreach ($clientesActivos as $cliente) {
            foreach ($cliente->licencias as $licencia) {
                if (!$licencia->activo || !$licencia->estadoActual) {
                    continue;
                }
                $versionActualCliente = $licencia->estadoActual->id_dev_version;
                $versionVigente = $versionesVigentes[$licencia->id_dev] ?? null;

                if ($versionVigente && $versionActualCliente != $versionVigente) {
                    $contador++;
                    break; // Con un módulo desactualizado ya cuenta el cliente
                }
            }
        }

        return $contador;
    }

    /**
     * Calcula el estado de un cliente: error, desactualizado o actualizado.
     */
    private function calcularEstadoCliente(Cliente $cliente): string
    {
        // Comprobar si tiene errores recientes en sus instancias
        $idsInstancias = $cliente->instancias->pluck('id');

        $tieneErrores = ClienteInstanciaVersion::whereIn('id_instancia', $idsInstancias)
            ->where('resultado', '!=', 'ok')
            ->where('fecha_actualizacion', '>=', Carbon::now()->subDays(7))
            ->exists();

        if ($tieneErrores) {
            return 'error';
        }

        // Comprobar si tiene módulos desactualizados
        $versionesVigentes = DesarrolloActual::pluck('id_dev_version', 'id_dev');

        $cliente->load('licencias.estadoActual');

        foreach ($cliente->licencias as $licencia) {
            if (!$licencia->activo || !$licencia->estadoActual) {
                continue;
            }
            $versionActual = $licencia->estadoActual->id_dev_version;
            $versionVigente = $versionesVigentes[$licencia->id_dev] ?? null;

            if ($versionVigente && $versionActual != $versionVigente) {
                return 'desactualizado';
            }
        }

        return 'actualizado';
    }
}
