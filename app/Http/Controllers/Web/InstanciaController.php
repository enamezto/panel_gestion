<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\ClienteInstancia;

class InstanciaController extends Controller
{
    public function show($clienteId, $instanciaId)
    {
        $cliente = Cliente::findOrFail($clienteId);

        $instancia = ClienteInstancia::with([
            'versiones.version.desarrollo',
        ])->where('id_cliente', $clienteId)->findOrFail($instanciaId);

        $historial = $instancia->versiones()
            ->with('version.desarrollo')
            ->orderByDesc('fecha_actualizacion')
            ->paginate(20);

        return view('clientes.instancia', compact('cliente', 'instancia', 'historial'));
    }
}
