<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClienteInstanciaVersion;

class HistoricoController extends Controller
{
    /**
     * GET /historico
     * Muestra el registro cronológico de instalaciones y actualizaciones.
     */
    public function index(Request $request)
    {
        $busqueda = $request->query('buscar');
        $resultado = $request->query('resultado');

        // Cargamos el histórico con relaciones (Eager Loading) para evitar N+1
        $query = ClienteInstanciaVersion::with([
            'instancia.cliente',
            'version.desarrollo'
        ]);

        // Filtro por nombre de cliente o PC
        if (!empty($busqueda)) {
            $query->where(function ($q) use ($busqueda){
                $q->whereHas('instancia.cliente', function($q2) use ($busqueda) {
                    $q2->where('nombre', 'LIKE', '%' . $busqueda . '%');
                })->orWhereHas('instancia', function($q2) use ($busqueda) {
                    $q2->where('nombre', 'LIKE', '%' . $busqueda . '%');
                });
            });

        }

        // Filtro por resultado (ok/error)
        if (!empty($resultado)) {
            $query->where('resultado', $resultado);
        }

        $registros = $query->orderByDesc('fecha_actualizacion')->paginate(20);

        return view('historico.index', compact('registros', 'busqueda', 'resultado'));
    }
}
