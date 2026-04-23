<?php

use App\Http\Controllers\VarianteController;
use Illuminate\Support\Facades\Route;

Route::prefix('variantes')->group(function () {
    Route::get('/', [VarianteController::class, 'listar'])
        ->name('varintes.listar')
        ->middleware('permiso:Variantes,Visualizar');

    Route::post('/', [VarianteController::class, 'crear'])
        ->name('variantes.crear')
        ->middleware('permiso:Variantes,Crear');

    Route::get('/{variante}', [VarianteController::class, 'obtener'])
        ->name('variantes.obtener')
        ->middleware('permiso:Variantes,Visualizar');

    Route::put('/{variante}', [VarianteController::class, 'editar'])
        ->name('variantes.editar')
        ->middleware('permiso:Variantes,Actualizar');

    Route::delete('/{variante}', [VarianteController::class, 'eliminar'])
        ->name('variantes.eliminar')
        ->middleware('permiso:Variantes,Eliminar');
});