<?php

use App\Http\Controllers\CatalogoController;
use Illuminate\Support\Facades\Route;

Route::prefix('catalogos')->group(function(){
    Route::get('/perfiles', [CatalogoController::class, 'getPerfiles'])
        ->name('catalogo.permisos')
        ->middleware('permiso:Catalogos,Visualizar');

    Route::get('/proveedores', [CatalogoController::class, 'getProveedores'])
        ->name('catalogo.proveedores')
        ->middleware('permiso:Catalogos,Visualizar');

    Route::get('/clientes', [CatalogoController::class, 'getClientes'])
        ->name('catalogo.clientes')
        ->middleware('permiso:Catalogos,Visualizar');
});
