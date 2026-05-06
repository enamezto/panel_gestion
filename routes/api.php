<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\DesarrolloController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {

    //--------------------------
    //RUTAS CLIENTECONTROLLER
    //--------------------------

    // 1. Registrar nueva instancia
    Route::post('/registro', [ClienteController::class, 'registrarInstancia']);

    Route::middleware('auth:sanctum')->group(function(){

        // 2. Consultar la última versión de un desarrollo concreto
        Route::get('/actualizaciones', [ClienteController::class, 'actualizaciones']);

        // 3. Confirmar instalación
        Route::post('/instalaciones/resultado', [ClienteController::class, 'registrarResultado']);
    });


    //--------------------------
    //RUTAS DESARROLLOCONTROLLER
    //--------------------------

    // 4. Registrar nueva versión
    Route::post('/versiones', [DesarrolloController::class, 'registrarNuevaVersion']);

    // 5. Listar la versión más reciente de cada desarrollo (también con buscador ?buscar=...)
    Route::get('/desarrollos/ultimas', [DesarrolloController::class, 'obtenerUltimasVersiones']);

    // 6. Ver todas las versiones de un desarrollo concreto
    Route::get('/desarrollos/{id}/versiones', [DesarrolloController::class, 'obtenerVersionesDesarrollo']);

    // 7. Ver todos los adjuntos de una versión concreta
    Route::get('/versiones/{id_version}/adjuntos', [DesarrolloController::class, 'obtenerAdjuntosVersion']);

});
