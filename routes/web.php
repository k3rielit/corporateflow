<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/barcode/{value}', \App\Http\Controllers\BarcodeController::class);
