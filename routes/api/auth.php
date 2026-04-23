<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('/')->group(function () {
    Route::get('permisos', [AuthController::class, 'obtenerPermisos'])
        ->middleware('auth:sanctum')
        ->name('obtener-permisos');

    Route::post('iniciar_sesion', [AuthController::class, 'iniciarSesion'])
        ->name('iniciar-sesion');

    Route::post('cerrar_sesion', [AuthController::class, 'cerrarSesion'])
        ->middleware('auth:sanctum')
        ->name('cerrar-sesion');
});