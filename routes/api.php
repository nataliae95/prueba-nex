<?php

use App\Enums\Position;
use App\Enums\Status;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ContactController;
use Illuminate\Support\Facades\Route;

//Route::middleware('auth:sanctum')->group(function () {
    // Rutas de Clientes: Recurso principal
    Route::apiResource('clients', ClientController::class);

    // Rutas de Contactos: Anidadas bajo clientes
    Route::apiResource('clients.contacts', ContactController::class)
        ->shallow()
        ->only(['index', 'store', 'update', 'destroy']);

    Route::get('/enums/status', function () {
        return response()->json(Status::choices());
    });

    Route::get('/enums/positions', function () {
        return response()->json(Position::choices());
    });

//});
