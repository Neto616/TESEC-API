<?php

use App\Http\Controllers\AtributoController;
use Illuminate\Support\Facades\Route;

Route::prefix('atributos')->group(function () {
    // Listado de atributos activos
    Route::get('/', [AtributoController::class, 'listar'])
        ->name('atributos.listar')
        ->middleware('permiso:Atributos,Visualizar');
    
    // Detalle de un atributo
    Route::get('/{atributo}', [AtributoController::class, 'ver'])
        ->name('atributos.consultar')
        ->middleware('permiso:Atributos,Visualizar');
    
    // Crear nuevo atributo en el catálogo
    Route::post('/', [AtributoController::class, 'crear'])
        ->name('atributos.crear')
        ->middleware('permiso:Atributos,Crear');
    
    // Editar atributo existente
    Route::put('/{atributo}', [AtributoController::class, 'editar'])
        ->name('atributos.editar')
        ->middleware('permiso:Atributos,Actualizar');
    
    // Eliminar (desactivar) atributo
    Route::delete('/{atributo}', [AtributoController::class, 'eliminar'])
        ->name('atributos.eliminar')
        ->middleware('permiso:Atributos,Eliminar');
});