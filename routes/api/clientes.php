<?php

use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Route;

Route::prefix('clientes')->group(function () {
    Route::get('/', [ClienteController::class, 'listar'])
        ->name('clientes.listar')
        ->middleware('permiso:Clientes,Visualizar');

    Route::get('/{cliente}', [ClienteController::class, 'ver'])
        ->name('clientes.consultar')
        ->middleware('permiso:Clientes,Visualizar');

    Route::post('/', [ClienteController::class, 'crear'])
        ->name('clientes.crear')
        ->middleware('permiso:Clientes,Crear');
    
    Route::delete('/{cliente}', [ClienteController::class, 'eliminar'])
        ->name('clientes.eliminar')
        ->middleware('permiso:Clientes,Eliminar');
});