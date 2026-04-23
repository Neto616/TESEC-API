<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckPermiso
{
    use ApiResponse;

    public function handle(Request $request, Closure $next, $seccion, $permiso)
    {
        $userId = Auth::user()?->id ?? auth('sanctum')->user()?->id;
        if (!$userId) {
            return $this->error("No autenticado", "No se detectó una sesión activa.", 401);
        }

        $data = AuthService::obtenerPermisosUsuario($userId);
        $tienePermiso = collect($data['permisos'])->contains(function ($p) use ($seccion, $permiso) {
            return $p['seccion'] === $seccion && $p['permiso'] === $permiso;
        });

        if (!$tienePermiso) {
            return $this->error(
                "Acceso denegado", 
                "No tienes permiso de $permiso en la sección $seccion", 
                403
            );
        }

        return $next($request);
    }
}