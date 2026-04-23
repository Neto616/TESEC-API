<?php

use App\Http\Controllers\ProveedorController;
use Illuminate\Support\Facades\Route;

Route::prefix('proveedores')->group(function () {
    Route::get('/', [ProveedorController::class, 'listar'])
        ->name('proveedores.listar')
        ->middleware('permiso:Proveedores,Visualizar');

    Route::get('/{proveedor}', [ProveedorController::class, 'ver'])
        ->name('proveedores.ver')
        ->middleware('permiso:Proveedores,Visualizar');

    Route::post('/', [ProveedorController::class, 'crear'])
        ->name('proveedores.crear')
        ->middleware('permiso:Proveedores,Crear');

    Route::post('/{proveedor}', [ProveedorController::class, 'editar'])
        ->name('proveedores.editar')
        ->middleware('permiso:Proveedores,Actualizar');

    Route::delete('/{proveedor}', [ProveedorController::class, 'eliminar'])
        ->name('proveedores.eliminar')
        ->middleware('permiso:Proveedores,Eliminar');
});