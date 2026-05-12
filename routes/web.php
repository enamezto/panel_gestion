<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ClienteWebController;
use App\Http\Controllers\Web\CatalogoController;
use App\Http\Controllers\Web\HistoricoController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/clientes', [ClienteWebController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/{id}', [ClienteWebController::class, 'show'])->name('clientes.show');
    Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo.index');
    Route::get('/catalogo/{id}', [CatalogoController::class, 'show'])->name('catalogo.show');
    Route::get('/historico', [HistoricoController::class, 'index'])->name('historico.index');
});

require __DIR__.'/auth.php';
