<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RepararResultadoRequest;
use App\Http\Requests\RegistrarResultadoRequest;
use App\Http\Requests\ConsultarActualizacionesRequest;
use App\Http\Requests\RegistrarInstanciaRequest;
use App\Models\Cliente;
use App\Models\ClienteInstancia;
use App\Models\ClienteDesarrollo;
use App\Models\ClienteInstanciaVersion;

class ClienteController extends Controller
{
    /**
     * GET /api/v1/actualizaciones
     * Devuelve la lista completa de desarrollos asignados al cliente y su última versión.
     */
    public function actualizaciones(Request $request)
    {
        $instancia = $request->user();
        $clienteId = $instancia->id_cliente;

        // 1. Buscamos TODOS los desarrollos contratados por este cliente
        $desarrollosContratados = \App\Models\ClienteDesarrollo::where('id_cliente', $clienteId)->get();

        $listaActualizaciones = [];

        // 2. Recorremos cada desarrollo contratado para buscar su última versión
        foreach ($desarrollosContratados as $relacion) {

            // Reutilizamos tu lógica actual para buscar la última versión publicada
            $desarrolloActual = \App\Models\DesarrolloActual::where('id_dev', $relacion->id_dev)
                ->with('version')
                ->first();

            // 3. Solo lo metemos en la lista SI tiene una versión publicada
            if ($desarrolloActual && $desarrolloActual->version) {
                $v = $desarrolloActual->version;

                $listaAdjuntos = [];

                $adjuntos = \App\Models\DesarrolloAdjunto::where('id_dev_version', $v->id)->get();

                foreach ($adjuntos as $adj) {

                $adjVersion = \App\Models\AdjuntoVersion::find($adj->id_adj_version);
                    if ($adjVersion) {

                    $adjBase = \App\Models\Adjunto::find($adjVersion->id_adj);
                        if ($adjBase) {
                            $listaAdjuntos[] = [
                                'arbol_carpeta' => $adjBase->arbol_carpeta,
                            ];
                        }
                    }
                }

                $listaActualizaciones[] = [
                    'id_dev'              => $relacion->id_dev,
                    'nombre'              => $relacion->desarrollo->nombre,
                    'id_dev_version'      => $v->id,
                    'version_major'       => $v->version_major,
                    'version_minor'       => $v->version_minor,
                    'version_patch'       => $v->version_patch,
                    'descripcion_cambios' => $v->descripcion_cambios,
                    'requiere_parada'     => $v->requiere_parada,
                    'hash'                => $v->hash,
                    'ruta'                => $v->ruta,
                    'adjuntos'            => $listaAdjuntos
                ];
            }
        }

        // 4. Devolvemos el array completo en formato JSON
        return response()->json($listaActualizaciones, 200);
    }

    /**
     * POST /api/v1/instalaciones/resultado
     */
    public function registrarResultado(RegistrarResultadoRequest $request)
    {
        $instancia = $request->user();

        $clienteId = $instancia->id_cliente;

        $relacion = \App\Models\ClienteDesarrollo::where('id_cliente', $clienteId)
            ->where('id_dev', $request->id_dev)
            ->first();


        if (!$relacion) {
            return response()->json([
                'error' => 'Este cliente no tiene permiso para usar este desarrollo'
            ], 403);
        }

        //Guardamos en el histórico
        $nuevaInstalacion = \App\Models\ClienteInstanciaVersion::create([
            'id_instancia' => $instancia->id,
            'id_cliente_desarrollo' => $relacion->id,
            'id_dev_version' => $request->id_dev_version,
            'fecha_actualizacion' => now(),
            'resultado' => $request->resultado,
            'observaciones' => $request->observaciones ?? 'Instalación/Actualización limpia'
        ]);

        return response()->json(['mensaje' => 'Resultado registrado', 'id_historial' => $nuevaInstalacion->id], 201);
    }

    /**
     * POST /api/v1/instancia
     * Registra una nueva instancia y genera el token único de seguridad.
     */
    public function registrarInstancia(RegistrarInstanciaRequest $request)
    {
        //$nuevoToken = \Illuminate\Support\Str::random(60);
        $cliente = $request->user();

        $instancia = ClienteInstancia::create([
            'id_cliente'      => $cliente->id,
            'nombre'          => $request->nombre,
            'host'            => $request->host,
            'tipo'            => $request->tipo,
            'ip'              => $request->ip ?? $request->ip(),
            //'registro_token'  => $nuevoToken,
            'fecha_alta'      => now(),
            'ultima_conexion' => now(),
            'ruta_listados'   => $request->ruta_listados,
        ]);

        $token = $instancia->createToken('instancia-token', ['instancia'])->plainTextToken;

        return response()->json([
            'mensaje'      => 'Instancia registrada con éxito',
            'id_instancia' => $instancia->id,
            //'token'        => $nuevoToken
            'token'        => $token
        ], 201);
    }

    /**
     * GET /api/v1/cliente
     * Devuelve la información del cliente autenticado mediante el token de cliente.
     * Este endpoint se usa una sola vez por instancia, durante el registro inicial.
     */
    public function obtenerCliente(Request $request){

        $cliente = $request->user();

        return response()->json([
            'id_cliente'        => $cliente->id,
            'nombre'            => $cliente->nombre,
            'codigo'            => $cliente->codigo,
            'version_a3erp'     => $cliente->version_a3erp,
            'activo'            => $cliente->activo,
        ], 200);
    }

}
