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


    Route::middleware(['auth:sanctum', 'abilities:cliente'])->group(function(){

        // 1. Obtener el ID enviando el Token de Cliente
        Route::get('/cliente', [ClienteController::class, 'obtenerCliente']);

        // 2. Registrar nueva instancia
        Route::post('/instancia', [ClienteController::class, 'registrarInstancia']);
    });

    Route::middleware(['auth:sanctum', 'abilities:instancia'])->group(function (){

        // 3. Consultar la última versión de un desarrollo concreto
        Route::get('/actualizaciones', [ClienteController::class, 'actualizaciones']);

        // 4. Confirmar instalación
        Route::post('/instalaciones/resultado', [ClienteController::class, 'registrarResultado']);
    });


    //--------------------------
    //RUTAS DESARROLLOCONTROLLER
    //--------------------------

    // 5. Registrar nueva versión
    Route::post('/versiones', [DesarrolloController::class, 'registrarNuevaVersion']);

    // 6. Listar la versión más reciente de cada desarrollo (también con buscador ?buscar=...)
    Route::get('/desarrollos/ultimas', [DesarrolloController::class, 'obtenerUltimasVersiones']);

    // 7. Ver todas las versiones de un desarrollo concreto
    Route::get('/desarrollos/{id}/versiones', [DesarrolloController::class, 'obtenerVersionesDesarrollo']);

    // 8. Ver todos los adjuntos de una versión concreta
    Route::get('/versiones/{id_version}/adjuntos', [DesarrolloController::class, 'obtenerAdjuntosVersion']);

});
