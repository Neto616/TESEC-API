<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthService
{
    public function __construct() {}

    public static function iniciarSesion($data)
    {
        $usuario = User::query()
            ->where([
                "email" => $data["email"],
                "estatus" => config("constants.common_status.activo"),
            ])
            ->first();

        if (!$usuario || !Hash::check($data["password"], $usuario->password)) {
            throw new \Exception("Correo o contraseña incorrectos", 409);
        }

        $deviceName = $data["device_name"] ?? "web_token";
        $token = $usuario->createToken($deviceName)->plainTextToken;
        $usuario->token = $token;

        return $usuario;
    }
    public static function cerrarSesion(User $user)
    {
        /** @var \Laravel\Sanctum\PersonalAccessToken $token */
        $token = $user->currentAccessToken();

        return $token->delete();
    }
    public static function obtenerPermisosUsuario($userId)
    {
        $usuario = User::with([
            "perfil.permisos" => function ($q) {
                $q->join(
                    "secciones",
                    "perfil_permisos.seccion_id",
                    "=",
                    "secciones.id",
                )->select("permisos.*", "secciones.nombre_seccion");
            },
        ])->find($userId);

        return [
            "usuario" => $usuario->nombre . " " . $usuario->apellidos,
            "perfil" => $usuario->perfil->perfil ?? "Sin Perfil",
            "permisos" => $usuario->perfil->permisos->map(function ($p) {
                return [
                    "seccion" => $p->nombre_seccion,
                    "permiso" => $p->permiso,
                ];
            }),
        ];
    }
}
