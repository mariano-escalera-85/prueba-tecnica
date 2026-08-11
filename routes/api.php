<?php

use App\Http\Controllers\EstadosController;
use App\Http\Controllers\MunicipiosController;
use Illuminate\Support\Facades\Route;

Route::get('estados/fetch', [EstadosController::class, 'fetch'])->name('estados.fetch')
    /*->middleware('auth:sanctum')*/;
Route::delete('estados', [EstadosController::class, 'destroy'])->name('estados.destroy')
    /*->middleware('auth:sanctum')*/;

Route::get('estados/{estado}/municipios', MunicipiosController::class)->name('estados.municipios.index');
