<?php

use App\Http\Controllers\Api\LoteClasificadoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/lotes/clasificar', [LoteClasificadoController::class, 'store']);
