<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Desarrollo;
use Carbon\Carbon;

class CatalogoController extends Controller
{
    /**
     * GET /catalogo
     * Muestra el catálogo de desarrollos (módulos) de SDi con su última versión.
     */
    public function index(Request $request)
    {
        // 1. Capturamos los filtros
        $busqueda = $request->query('buscar');
        $mostrarInactivos = $request->boolean('inactivos');

        // 2. Preparamos la consulta trayendo las versiones para no tener el problema N+1
        $query = Desarrollo::with('versiones')->withCount('versiones');

        // Aplicamos filtro de búsqueda
        if (!empty($busqueda)) {
            $query->where('nombre', 'LIKE', '%' . $busqueda . '%');
        }

        // Filtramos inactivos si el checkbox no está marcado
        if (!$mostrarInactivos) {
            $query->where('activo', true);
        }

        // 3. Obtenemos y formateamos los datos
        $desarrollos = $query->orderBy('nombre')
            ->get()
            ->map(function ($dev) {
                // Buscamos la última versión ordenando la colección en memoria
                $ultimaVersion = $dev->versiones->sortBy([
                    ['version_major', 'desc'],
                    ['version_minor', 'desc'],
                    ['version_patch', 'desc'],
                ])->first();

                return [
                    'id'              => $dev->id,
                    'nombre'          => $dev->nombre,
                    'descripcion'     => $dev->descripcion,
                    'tipo'            => $dev->tipo,
                    'activo'          => $dev->activo,
                    'total_versiones' => $dev->versiones_count,
                    'version_actual'  => $ultimaVersion ? "v{$ultimaVersion->version_major}.{$ultimaVersion->version_minor}.{$ultimaVersion->version_patch}" : '—',
                    'fecha_version'   => $ultimaVersion ? $ultimaVersion->fecha : null,
                    'a3erp_min'       => $ultimaVersion->version_a3erp_min ? 'v' . $ultimaVersion->version_a3erp_min : '—',
                    'a3erp_max'       => $ultimaVersion->version_a3erp_max ? 'v' . $ultimaVersion->version_a3erp_max : '∞',
                ];
            });

        return view('catalogo.index', [
            'desarrollos'      => $desarrollos,
            'busqueda'         => $busqueda,
            'mostrarInactivos' => $mostrarInactivos,
        ]);
    }
    public function show($id)
    {
        $desarrollo = Desarrollo::with([
            'versiones' => function($q) {
                $q->orderByDesc('version_major')
                ->orderByDesc('version_minor')
                ->orderByDesc('version_patch');
            },
            'actual.version',   // versión vigente del catálogo
        ])->findOrFail($id);

        // Clientes que tienen este desarrollo contratado
        $clientesConLicencia = \App\Models\ClienteDesarrollo::with([
            'cliente',
            'estadoActual.version',
        ])
        ->where('id_dev', $id)
        ->where('activo', true)
        ->get();

        // Versión vigente (la del catálogo DesarrolloActual)
        $versionVigente = $desarrollo->actual?->version;

        return view('catalogo.show', compact('desarrollo', 'clientesConLicencia', 'versionVigente'));
    }
}
