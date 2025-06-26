<?php

use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

// Rota para buscar CNPJ em uma API externa https://brasilapi.com.br
Route::get('cnpj/{cnpj}/fetch', [SupplierController::class, 'fetchCnpj']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::apiResource('suppliers', SupplierController::class);

});
