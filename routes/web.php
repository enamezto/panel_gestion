<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ClienteWebController;
use App\Http\Controllers\Web\CatalogoController;
use App\Http\Controllers\Web\HistoricoController;
use App\Http\Controllers\Web\BusquedaController;
use App\Http\Controllers\Web\InstanciaController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/clientes', [ClienteWebController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/create', [ClienteWebController::class, 'create'])->name('clientes.create');
    Route::post('/clientes', [ClienteWebController::class, 'store'])->name('clientes.store');
    Route::get('/clientes/{id}', [ClienteWebController::class, 'show'])->name('clientes.show');
    Route::post('/clientes/{id}/licencias', [ClienteWebController::class, 'addLicencia'])->name('clientes.licencias.add');
    Route::delete('/clientes/{id}/licencias/{devId}', [ClienteWebController::class, 'removeLicencia'])->name('clientes.licencias.remove');

    Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo.index');
    Route::get('/catalogo/{id}', [CatalogoController::class, 'show'])->name('catalogo.show');

    Route::get('/historico', [HistoricoController::class, 'index'])->name('historico.index');

    Route::get('/buscar', [BusquedaController::class, 'index'])->name('buscar.index');

    Route::get('/clientes/{id}/instancias/{instanciaId}', [InstanciaController::class, 'show'])->name('clientes.instancias.show');

});

require __DIR__.'/auth.php';
