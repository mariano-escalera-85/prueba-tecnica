<?php

use App\DataTables\EstadosDataTable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', function (EstadosDataTable $dataTable) {
    return $dataTable->render('home');
})->name('home')->middleware('auth:web');
