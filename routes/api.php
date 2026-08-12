<?php

use App\Http\Controllers\EstadosController;
use App\Http\Controllers\MunicipiosController;
use Illuminate\Support\Facades\Route;

Route::prefix('estados')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [EstadosController::class, 'index'])->name('api.estados.index');
        Route::get('/fetch', [EstadosController::class, 'fetch'])->name('api.estados.fetch');
        Route::delete('/', [EstadosController::class, 'destroy'])->name('api.estados.destroy');
        Route::get('/{estado}/municipios', MunicipiosController::class)->name('api.estados.municipios.index');
    });
});
