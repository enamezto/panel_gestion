<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Models\Desarrollo;
use App\Models\DesarrolloVersion;
use App\Models\Adjunto;
use App\Models\AdjuntoVersion;
use App\Models\DesarrolloAdjunto;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\NuevaVersionRequest;


class DesarrolloController extends Controller
{
    /**
     * POST /api/v1/versiones
     * Registra una nueva versión disponible en el sistema.
     */
    public function registrarNuevaVersion(NuevaVersionRequest $request)
    {
        DB::beginTransaction();

        try{
            $id_dev = $request->idModule;
            // 1. Si NO viene el id_dev, es un Desarrollo nuevo. Lo creamos primero.
        if (empty($id_dev)) {
            $desarrollo = Desarrollo::create([
                'nombre' => $request->moduleName, // Ej: "Fitosanitarios"
                'descripcion' => $request->description,
                'tipo' => $request->type,
                'activo' => $request->active,
                'fecha' => $request->date,
            ]);
            // Capturamos el ID del desarrollo recién creado
            $id_dev = $desarrollo->id;
        } else {
            // Si ya existe, buscamos el nombre en la BD para no fallar
            $desarrollo = Desarrollo::findOrFail($id_dev);
        }

        // 2. Creamos la nueva versión asociada a ese ID (sea el nuevo o el que ya venía)
        $nuevaVersion = DesarrolloVersion::create([
            'id_dev' => $id_dev,
            'version_major' => $request->major,
            'version_minor' => $request->minor,
            'version_patch' => $request->patch,
            'fecha' => $request->date,
            'descripcion_cambios' => $request->changes,
            'version_a3erp_min'   => $request->a3erpmin,
            'version_a3erp_max'   => $request->a3erpmax,
            'requiere_parada' => $request->requiresStop,
            'hash' => $request->hash,
            'ruta' => $request->path,
        ]);

        $tiposDB = \App\Models\AdjuntoTipo::pluck('id', 'nombre')->toArray();
        $tiposAdj = array_change_key_case($tiposDB, CASE_LOWER);

        // 3. Procesar el array de Adjuntos
        foreach ($request->adjuntos as $adjData) {

            $tipoKey = strtolower($adjData['tipo']);
            $tipoFinal = $tiposAdj[$tipoKey] ?? 1;

            $adjuntoBase = Adjunto::where('id_tipo', $tipoFinal)->where('arbol_carpeta', $adjData['ruta'])->first();

            if(!$adjuntoBase){
                $adjuntoBase = Adjunto::create([
                    'id_tipo'       => $tipoFinal,
                    'descripcion'   => $adjData['description'] ?? null,
                    'arbol_carpeta' => $adjData['ruta'],
                    'principal'     => $adjData['principal'] ?? null
                ]);
            }

            $adjVersion = AdjuntoVersion::create([
                'id_adj'        => $adjuntoBase->id,
                'version_major' => $adjData['major'],
                'version_minor' => $adjData['minor'],
                'version_patch' => $adjData['patch'],
                'hash'          => $adjData['hash'],
                'fecha'         => $adjData['date'] ?? null
            ]);

            DesarrolloAdjunto::create([
                'id_dev_version' => $nuevaVersion->id,
                'id_adj_version' => $adjVersion->id,
                'obligatorio' => $adjData['obligatorio'],
                'orden' => $adjData['order'] ?? 1,
            ]);
        }

        // Si todo ha ido bien, confirmamos los cambios en la Base de Datos
        DB::commit();

        return response()->json([
            'mensaje' => 'Versión registrada correctamente',
            'id_desarrollo' => $id_dev,
            'id_version' => $nuevaVersion->id
        ], 201);

        } catch (\Exception $e) {
            // Si hay algún error, deshacemos los inserts para no dejar datos a medias
            DB::rollBack();

            return response()->json([
                'error' => 'Hubo un problema al registrar la versión: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/v1/desarrollos/ultimas
     * Devuelve las últimas versiones de todos los desarrollos o solo algunos,
     * según lo que mande el desarrollador.
     */
    public function obtenerUltimasVersiones(Request $request){
        // 1. Miramos si nos han pasado el parámetro ?buscar=
        $busqueda = $request->query('buscar');

        $query = Desarrollo::query();

        // 2. Si hay texto, filtramos. Si no, esta línea se ignora y busca todos.
        if (!empty($busqueda)) {
            $query->where('nombre', 'LIKE', '%' . $busqueda . '%');
        }

        $desarrollos = $query->get();
        $resultadoFinal = [];

        // 3. Montamos la respuesta ligera
        foreach ($desarrollos as $desarrollo) {
            $ultimaVersion = $desarrollo->versiones()
                ->orderByDesc('version_major')
                ->orderByDesc('version_minor')
                ->orderByDesc('version_patch')
                ->first();

            if (!$ultimaVersion) continue;

            $resultadoFinal[] = [
                'id_desarrollo_version' => $ultimaVersion->id, // El dato CLAVE para el siguiente paso
                'nombre'                => $desarrollo->nombre,
                'major'                 => (string) $ultimaVersion->version_major,
                'minor'                 => (string) $ultimaVersion->version_minor,
                'patch'                 => (string) $ultimaVersion->version_patch,
                'fecha'                 => $ultimaVersion->fecha,
                'descripcion_cambios'   => $ultimaVersion->descripcion_cambios,
                'a3erpmin'              =>$ultimaVersion->version_a3erp_min,
                'a3erpmax'              =>$ultimaVersion->version_a3erp_max
            ];
        }

        return response()->json($resultadoFinal, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * GET /api/v1/versiones/{id}/adjuntos
     * Devuelve los adjuntos de una versión específica
     */
    public function obtenerAdjuntosVersion($id_version)
    {
        // Buscamos la versión por el ID que nos ha pasado
        $version = DesarrolloVersion::with('adjunto.versionAdjunto.adjunto')->findOrFail($id_version);

        $adjuntos = [];

        // Recorremos sus adjuntos (usando la lógica de tus tablas)
        foreach ($version->adjunto as $devAdjunto) {
            $adjVersion = $devAdjunto->versionAdjunto;

            if($adjVersion && $adjVersion->adjunto){
                $adjunto = $adjVersion->adjunto;

                $adjuntos[] = [
                'tipo'      => $adjunto->tipo->nombre ?? null,
                'ruta'      => $adjunto->arbol_carpeta,
                'major'     => (string) $adjVersion->version_major,
                'minor'     => (string) $adjVersion->version_minor,
                'patch'     => (string) $adjVersion->version_patch,
                'hash'      => $adjVersion->hash,
                'principal' => $adjunto->principal ?? ''
            ];
            }
        }

        return response()->json([
            'adjuntos' => $adjuntos
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * GET /api/v1/desarrollos/{id}/versiones
     * Devuelve todas las versiones que ha habido de un desarrollo concreto.
     */
    public function obtenerVersionesDesarrollo($id){
        // 1. Buscamos el desarrollo. Si no existe, lanza un 404 automático.
        $desarrollo = Desarrollo::findOrFail($id);

        // 2. Obtenemos TODAS sus versiones ordenadas lógicamente de más nueva a más antigua
        $versiones = $desarrollo->versiones()
            ->orderByDesc('version_major')
            ->orderByDesc('version_minor')
            ->orderByDesc('version_patch')
            ->get();

        // Si el desarrollo existe pero aún no tiene versiones, devolvemos un array vacío
        if ($versiones->isEmpty()) {
            return response()->json([], 200);
        }

        // 3. Mapeamos la colección para darle el formato exacto que tu compañero necesita
        $resultadoFinal = $versiones->map(function ($version) use ($desarrollo) {
            return [
                // Siempre es buena idea mandar el ID para que él pueda pedir los adjuntos después
                'id_desarrollo_version' => $version->id,
                'idModule'              => $desarrollo->id,
                'moduleName'            => $desarrollo->nombre,
                'major'                 => (string) $version->version_major,
                'minor'                 => (string) $version->version_minor,
                'patch'                 => (string) $version->version_patch,
                'fecha'                 => $version->fecha,
                'descripcion_cambios'   => $version->descripcion_cambios,
                'a3erpmin'              => $version->version_a3erp_min,
                'a3erpmax'              => $version->version_a3erp_max
            ];
        });

        // 4. Retornamos la respuesta profesional
        return response()->json($resultadoFinal, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
