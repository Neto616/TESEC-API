<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsuarioRequest;
use App\Models\User;
use App\Services\UserService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    use ApiResponse;

    public function __construct() {
    }
    public function listar(Request $request){
        $paginacion = $request->input("per_page", 10);
        if($paginacion > 100) $paginacion = 100;

        try {
            $except = function($q){
                $q->whereNotNull('perfil_id');
            };
            $usuarios = UserService::getAllReg($paginacion, exception: $except);
        } catch (\Exception $e) {
            return $this->error('Error al listar los usuarios', $e->getMessage(), $e->getCode());
        }
        return $this->success('Listado de usuarios', $usuarios);
    }
    public function obtener(User $user){
        try {
            $service = new UserService($user);
            $usuario = $service->getById();

            return $this->success('Informacion del usuario', $usuario);
        } catch (\Exception $e) {
            return $this->error('Error al obtener al usuario', $e->getMessage(), $e->getCode());
        }
    }
    public function crear(UsuarioRequest $request) {
        $validated = $request->validated();
        try {
            $usuario = UserService::registrar($validated);
        } catch (\Exception $e) {
            return $this->error('Error al crear usuario', $e->getMessage(), $e->getCode());
        }
        return $this->success('Usuario creado exitosamente', $usuario, 201);
    }
    public function editar(UsuarioRequest $request, User $user) {
        $validated = $request->validated();
        try {
            $service = new UserService($user);
            $user_data = $service->editar($validated);
        } catch (\Exception $e) {
            return $this->error('Error al editar usuario', $e->getMessage(), $e->getCode());
        }
        return $this->success('Usuario actualizado exitosamente', $user_data);
    }
    public function editarContrasena(User $user){
        try {
            $service = new UserService($user);
            $new_pass = $service->editarContrasena();

            return $this->success('Contraseña nueva', $new_pass);
        } catch (\Exception $e) {
            return $this->error('Error al crear contraseña nueva', $e->getMessage(), $e->getCode());
        }
    }
    public function eliminar(User $user) {
        try {
            $service = new UserService($user);
            $user_data = $service->eliminar();
        } catch (\Exception $e) {
            return $this->error('Error al eliminar usuario', $e->getMessage(), $e->getCode());
        }
        return $this->success('Usuario eliminado exitosamente', $user_data);
    }
}
