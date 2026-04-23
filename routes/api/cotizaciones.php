<?php

use App\Http\Controllers\CotizacionController;
use Illuminate\Support\Facades\Route;

Route::prefix('cotizaciones')->group(function () {
    Route::get('/', [CotizacionController::class, 'listar'])
        ->name('cotizaciones.listar')
        ->middleware('permiso:Productos,Visualizar');

    Route::get('/{cotizacion}', [CotizacionController::class, 'ver'])
        ->name('cotizaciones.ver')
        ->middleware('permiso:Productos,Visualizar');

    Route::post('/', [CotizacionController::class, 'crear'])
        ->name('cotizacion.crear')
        ->middleware('permiso:Productos,Actualizar');

    Route::put('/{cotizacion}', [CotizacionController::class, 'editar'])
        ->name('cotizacion.editar')
        ->middleware('permiso:Productos,Actuallizar');

    Route::patch('/editar-estatus/{cotizacion}', [CotizacionController::class, 'changeEstatus'])
        ->name('cotizacion.editar_estatus')
        ->middleware('permiso:Productos,Actualizar');
});