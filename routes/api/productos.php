<?php

use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

Route::prefix("/productos")->group(function () {
    Route::get('/', [ProductoController::class, 'listar'])
        ->name('productos.listar')
        ->middleware('permiso:Productos,Visualizar');

    Route::post('/', [ProductoController::class, 'crear'])
        ->name('productos.crear')
        ->middleware('permiso:Productos,Crear');

    Route::get('/{producto}', [ProductoController::class, 'ver'])
    ->name('productos.consulta')
    ->middleware('permiso:Productos,Visualizar');

    Route::post('/{producto}', [ProductoController::class, 'editar'])
        ->name('productos.editar')
        ->middleware('permiso:Productos,Actualizar');

    Route::delete('/{producto}', [ProductoController::class, 'eliminar'])
        ->name('productos.eliminar')
        ->middleware('permiso:Productos,Eliminar');
});
