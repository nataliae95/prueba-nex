<?php

use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ContactController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/

// Rutas de Clientes: Recurso principal
Route::apiResource('clients', ClientController::class);

// Rutas de Contactos: Anidadas bajo clientes
Route::apiResource('clients.contacts', ContactController::class)
    ->shallow()
    ->only(['index', 'store', 'update', 'destroy']);
