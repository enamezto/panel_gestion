<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Cliente;
use App\Models\ClienteInstanciaVersion;
use App\Models\ClienteDesarrolloActual;
use App\Models\DesarrolloActual;

class ClienteWebController extends Controller
{

    // GET /clientes
    // Muestra el listado de clientes con sus datos básicos.
    public function index(Request $request)
    {
        // Filtros: por nombre y por mostrar inactivos
        $busqueda = $request->query('buscar');
        $mostrarInactivos = $request->boolean('inactivos');

        $versionesVigentes = DesarrolloActual::pluck('id_dev_version', 'id_dev');

        $instanciasConErrores = ClienteInstanciaVersion::where('resultado', '!=', 'ok')
            ->where('fecha_actualizacion', '>=', Carbon::now()->subDays(7))
            ->pluck('id_instancia')
            ->toArray();

        $query = Cliente::with(['instancias', 'licencias.estadoActual'])
            ->withCount('instancias');

        // Filtro por nombre
        if (!empty($busqueda)) {
            $query->where('nombre', 'LIKE', '%' . $busqueda . '%');
        }

        // Por defecto solo activos, salvo que el usuario active el filtro
        if (!$mostrarInactivos) {
            $query->where('activo', true);
        }

        $clientes = $query->orderBy('nombre')
            ->get()
            ->map(function ($cliente) use ($versionesVigentes, $instanciasConErrores){
                return [
                    'id'              => $cliente->id,
                    'nombre'          => $cliente->nombre,
                    'codigo'          => $cliente->codigo,
                    'version_a3erp'   => $cliente->version_a3erp,
                    'instancias'      => $cliente->instancias_count,
                    'ultima_conexion' => $cliente->instancias->max('ultima_conexion'),
                    'activo'          => $cliente->activo,
                    'estado'          => $cliente->calcularEstadoGeneral($versionesVigentes),
                ];
            });

        return view('clientes.index', [
            'clientes'         => $clientes,
            'busqueda'         => $busqueda,
            'mostrarInactivos' => $mostrarInactivos,
        ]);
    }
    public function show($id)
    {
        $cliente = Cliente::with(['instancias', 'licencias.desarrollo', 'licencias.estadoActual.version'])->findOrFail($id);

        $versionesVigentes = DesarrolloActual::with('version')
            ->get()
            ->keyBy('id_dev');

        $licenciasDesactualizadas = $cliente->licencias->filter(function($licencia) use ($versionesVigentes) {

        if(!$licencia->estadoActual) return false;

        $vigente = $versionesVigentes[$licencia->id_dev] ?? null;
        if(!$vigente) return false;

        return $licencia->estadoActual->id_dev_version !== $vigente->id_dev_version;
        })->map(function($licencia) use ($versionesVigentes){
            $vigente = $versionesVigentes[$licencia->id_dev];
            $licencia->version_instalada = $licencia->estadoActual->version ?? null;
            $licencia->version_disponible = $vigente->version ?? null;
            return $licencia;
        });

        return view('clientes.show', compact('cliente', 'licenciasDesactualizadas'));
    }
}

