<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request; // ESTO ES VITAL
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        then: function() {
            Route::middleware('api')
            ->prefix('api')
            ->group(function(){
                Route::group([], base_path('routes/api/auth.php'));
                Route::middleware('auth:sanctum')->group(function () {
                    Route::group([], base_path('routes/api/atributos.php'));
                    Route::group([], base_path('routes/api/catalogos.php'));
                    Route::group([], base_path('routes/api/cotizaciones.php'));
                    Route::group([], base_path('routes/api/clientes.php'));
                    Route::group([], base_path('routes/api/productos.php'));
                    Route::group([], base_path('routes/api/proveedores.php'));
                    Route::group([], base_path('routes/api/usuarios.php'));
                    Route::group([], base_path('routes/api/variantes.php'));
                });
            });
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'permiso' => \App\Http\Middleware\CheckPermiso::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Ese registro no existe en la base de datos (ID: '.$request->segment(3).')',
            ], 404);
        });

    })->create();