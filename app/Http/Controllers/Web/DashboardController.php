<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // De momento devolvemos datos vacíos.
        // En la siguiente fase los traeremos de la base de datos.
        return view('dashboard.index', [
            'totalClientes' => 0,
            'totalInstancias' => 0,
            'clientesDesactualizados' => 0,
            'erroresRecientes' => 0,
        ]);
    }
}
