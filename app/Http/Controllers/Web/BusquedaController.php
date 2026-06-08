<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Desarrollo;

class BusquedaController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->query('q');

        if (empty($query) || strlen($query) < 2) {
            return response()->json(['clientes' => [], 'desarrollos' => []]);
        }

        $clientes = Cliente::where('nombre', 'LIKE', '%' . $query . '%')
            ->orWhere('codigo', 'LIKE', '%' . $query . '%')
            ->limit(5)
            ->get(['id', 'nombre', 'codigo']);

        $desarrollos = Desarrollo::where('nombre', 'LIKE', '%' . $query . '%')
            ->limit(5)
            ->get(['id', 'nombre', 'tipo']);

        return response()->json([
            'clientes' => $clientes,
            'desarrollos' => $desarrollos,
        ]);
    }
}
