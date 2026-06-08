<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class HealthController extends Controller
{
    public function check()
    {
        try {
            DB::connection()->getPdo();

            return response()->json([
                'api' => 'OK',
                'database' => 'Conectada',
                'motor' => 'PostgreSQL',
                'status' => 200
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'api' => 'OK',
                'database' => 'Desconectada',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
}
