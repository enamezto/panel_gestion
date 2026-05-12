<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Cliente;
use App\Models\ClienteInstancia;
use App\Models\ClienteInstanciaVersion;
use App\Models\DesarrolloActual;

class DashboardController extends Controller
{
    public function index()
    {
        $versionesVigentes = DesarrolloActual::pluck('id_dev_version', 'id_dev');
        // 1. Clientes activos
        $totalClientes = Cliente::where('activo', true)->count();

        // 2. Total de instancias (PCs)
        $totalInstancias = ClienteInstancia::count();

        // 3. Errores recientes (instalaciones fallidas en los últimos 7 días)
        $erroresRecientes = ClienteInstanciaVersion::where('resultado', '!=', 'ok')
            ->where('fecha_actualizacion', '>=', Carbon::now()->subDays(7))
            ->count();

        $instanciasConErrores = ClienteInstanciaVersion::where('resultado', '!=', 'ok')
            ->where('fecha_actualizacion', '>=', Carbon::now()->subDays(7))
            ->pluck('id_instancia')
            ->toArray();

        $clientesQuery = Cliente::where('activo', true)
            ->with(['instancias', 'licencias.estadoActual'])
            ->withCount('instancias')
            ->orderBy('nombre')
            ->get();

        // 4. Clientes desactualizados (tienen al menos un módulo en versión antigua)
        $clientesDesactualizados = 0;

        // 5. Listado de clientes con su estado calculado
        $clientes = $clientesQuery->map(function ($cliente) use ($versionesVigentes, $instanciasConErrores, &$clientesDesactualizados){
            $estado = $cliente->calcularEstadoGeneral($versionesVigentes, $instanciasConErrores);
            if($estado === 'desactualizado'){
                $clientesDesactualizados++;
            }
                return [
                    'id'              => $cliente->id,
                    'nombre'          => $cliente->nombre,
                    'version_a3erp'   => $cliente->version_a3erp,
                    'instancias'      => $cliente->instancias_count,
                    'ultima_conexion' => $cliente->instancias->max('ultima_conexion'),
                    'estado'          => $estado,
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
}
