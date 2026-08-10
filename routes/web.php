<?php

use App\Http\Controllers\EstadosController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/estados', [EstadosController::class, 'index'])->name('estados.index');
