<?php

use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::prefix("/usuarios")->group(function () {
    Route::get("/", [UsuarioController::class, "listar"])
        ->name("usuarios.listar")
        ->middleware('permiso:Usuarios,Visualizar');

    Route::post("/", [UsuarioController::class, "crear"])
        ->name("usuarios.crear")
        ->middleware('permiso:Usuarios,Crear');

    Route::get("/{user}", [UsuarioController::class, "obtener"])
        ->name("usuarios.obtener")
        ->middleware('permiso:Usuarios,Visualizar');

    Route::patch("/{user}", [UsuarioController::class, "editarContrasena"])
        ->name("usuarios.cambiar_contra")
        ->middleware('permiso:Usuarios,Actualizar');

    Route::put("/{user}", [UsuarioController::class, "editar"])
        ->name("usuarios.editar")
        ->middleware('permiso:Usuarios,Actualizar');

    Route::delete("/{user}", [UsuarioController::class, "eliminar"])
        ->name("usuarios.eliminar")
        ->middleware('permiso:Usuarios,Eliminar');
});