<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct() {}

    public function iniciarSesion(AuthRequest $request){
        try {
            $token = AuthService::iniciarSesion($request->validated());
        } catch (\Exception $e) {
            return $this->error('Error al iniciar sesión.', $e->getMessage(), $e->getCode());
        }
        return $this->success('Sesión inciada con exito.', $token);
    }
    public function cerrarSesion(Request $request){
        $usuario = $request->user();
        try{
            if(!$usuario)
                return $this->error("Error al cerrar sesión.", "No estas autenticado", 409);
            AuthService::cerrarSesion($request->user());
            return  $this->success('Sesión cerrada con éxito.', []);
        }catch(\Exception $e){
            return $this->error("Error al cerrar sesión", $e->getMessage(), $e->getCode());
        }
    }
    public function obtenerPermisos(Request $request){
        try {
            $userId = Auth::user()->id;

            $data = AuthService::obtenerPermisosUsuario($userId);
            return $this->success('Permisos obtenidos con éxito', $data);
        } catch (\Exception $e) {
            return $this->error("Error al obtener permisos", $e->getMessage(), $e->getCode());
        }
    }
}
