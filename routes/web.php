<?php

use App\Http\Controllers\EstadosController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/estados', [EstadosController::class, 'index'])->name('estados.index');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
