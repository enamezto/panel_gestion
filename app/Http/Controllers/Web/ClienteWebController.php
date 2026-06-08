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
                    'estado'          => $cliente->calcularEstadoGeneral($versionesVigentes, $instanciasConErrores),
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
        $cliente = Cliente::with([
            'instancias',
            'licencias.desarrollo',
            'licencias.estadoActual.version'
        ])->findOrFail($id);

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

        $idsContratados = $cliente->licencias->pluck('id_dev')->toArray();
        $desarrollosDisponibles = \App\Models\Desarrollo::where('activo', true)
            ->whereNotIn('id', $idsContratados)
            ->orderBy('nombre')
            ->get();

        $erroresRecientes = ClienteInstanciaVersion::whereIn(
                'id_instancia',
                $cliente->instancias->pluck('id')
            )
            ->where('resultado', '!=', 'ok')
            ->where('fecha_actualizacion', '>=', Carbon::now()->subDays(7))
            ->with(['version.desarrollo', 'instancia'])
            ->orderByDesc('fecha_actualizacion')
            ->get();

        return view('clientes.show', compact(
            'cliente',
            'licenciasDesactualizadas',
            'desarrollosDisponibles',
            'erroresRecientes'
        ));
    }

    // GET /clientes/create
    // Muestra el formulario de creación de un nuevo cliente
    public function create()
    {
        return view('clientes.create');
    }

    // POST /clientes
    // Guarda el nuevo cliente y genera el token Sanctum
    public function store(Request $request)
    {
        $request->validate([
            'nombre'        => 'required|string|max:255',
            'codigo'        => 'required|string|max:50|unique:clientes,codigo',
            'version_a3erp' => 'nullable|string|max:20',
        ], [
            'nombre.required'   => 'El nombre del cliente es obligatorio.',
            'codigo.required'   => 'El código del cliente es obligatorio.',
            'codigo.unique'     => 'Ya existe un cliente con ese código.',
        ]);

        // Crear el cliente
        $cliente = Cliente::create([
            'nombre'        => $request->nombre,
            'codigo'        => strtoupper($request->codigo),
            'version_a3erp' => $request->version_a3erp,
            'activo'        => true,
        ]);

        // Generar el token Sanctum con ability 'cliente'
        $token = $cliente->createToken('cliente-token', ['cliente'])->plainTextToken;

        // Redirigir a una vista que muestre el token UNA SOLA VEZ
        return view('clientes.token', [
            'cliente' => $cliente,
            'token'   => $token,
        ]);
    }
    // POST /clientes/{id}/licencias
    public function addLicencia(Request $request, $id)
    {
        $request->validate([
            'id_dev' => 'required|exists:desarrollos,id',
        ], [
            'id_dev.required' => 'Debes seleccionar un desarrollo.',
            'id_dev.exists'   => 'El desarrollo seleccionado no existe.',
        ]);

        $cliente = Cliente::findOrFail($id);

        // Comprobamos que no tenga ya esta licencia
        $yaExiste = $cliente->licencias()->where('id_dev', $request->id_dev)->exists();

        if ($yaExiste) {
            return redirect()->route('clientes.show', $id)
                ->with('error', 'Este cliente ya tiene contratado ese desarrollo.');
        }

        $cliente->licencias()->create([
            'id_dev' => $request->id_dev,
            'activo' => true,
        ]);

        return redirect()->route('clientes.show', $id)
            ->with('success', 'Licencia añadida correctamente.');
    }

    // DELETE /clientes/{id}/licencias/{devId}
    public function removeLicencia($id, $devId)
    {
        $cliente = Cliente::findOrFail($id);

        $licencia = $cliente->licencias()->where('id_dev', $devId)->firstOrFail();
        $licencia->delete();

        return redirect()->route('clientes.show', $id)
            ->with('success', 'Licencia eliminada correctamente.');
    }
}

