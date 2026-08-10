<?php

use App\Http\Controllers\EstadosController;
use Illuminate\Support\Facades\Route;

Route::resource('estados', EstadosController::class)->only(['store'])
    /*->middleware('auth:sanctum')*/;
Route::delete('estados', [EstadosController::class, 'destroyAll'])->name('estados.destroyAll')
    /*->middleware('auth:sanctum')*/;
