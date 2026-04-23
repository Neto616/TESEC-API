<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use Closure;

class UserService
{
    private $usuario;
    public function __construct(User $usuario)
    {
        $this->usuario = $usuario;
    }
    private static function userExist($user_exist, ?Closure $exception = null)
    {
        $query = User::query()->where("email", $user_exist["email"]);

        if ($exception) {
            $exception($query);
        }

        $verify_data = $query->exists();

        return $verify_data;
    }
    public static function getAllReg($perPage = 10, ?Closure $exception = null)
    {
        $query = User::query();

        if ($exception != null) {
            $exception($query);
        }

        return $query->paginate($perPage);
    }
    public static function registrar($user_data)
    {
        return DB::transaction(function () use ($user_data) {
            $fn_exception = function ($q) {
                $q->where("estatus", config("constants.common_status.activo"));
            };

            $user_exist = self::userExist($user_data, $fn_exception);
            if ($user_exist) {
                throw new \Exception(
                    "Verificar correo, ya existe un usuario con ese correo.",
                    409,
                );
            }
            $new_user = User::create(
                array_merge($user_data, [
                    "password" => bcrypt(Str::password(10, true, true)),
                ]),
            );
            return $new_user;
        });
    }
    public function editar($new_data)
    {
        return DB::transaction(function () use ($new_data) {
            $user = $this->getById();
            if ($new_data["email"] ?? false) {
                $fn_exception = function ($q) {
                    $q->whereNot("id", $this->usuario->id)->where(
                        "estatus",
                        config("constants.common_status.activo"),
                    );
                };

                if (self::userExist($new_data, $fn_exception)) {
                    throw new \Exception(
                        "Verificar correo, ya existe un usuario con ese correo.",
                        409,
                    );
                }
            }
            $user->update($new_data);
            return $user->fresh();
        });
    }
    public function editarContrasena()
    {
        return DB::transaction(function () {
            $this->getById();
            $password = Str::password(10, true, true);
            $this->usuario->update(["password" => bcrypt($password)]);

            $this->usuario->fresh();
            $this->usuario->new_password = $password;
            return $this->usuario;
        });
    }
    public function eliminar()
    {
        return DB::transaction(function () {
            $this->getById();
            $this->usuario->update([
                "estatus" => config("constants.common_status.inactivo"),
            ]);

            return $this->usuario->fresh();
        });
    }
    public function getById($relation = [])
    {
        $user = User::query()
            ->where("id", $this->usuario->id)
            ->when(!empty($relation), fn($q) => $q->with($relation))
            ->first();

        if (!$user) {
            throw new \Exception(
                "No se encontro un usuario con el identificador {$this->usuario->id}",
                404,
            );
        }
        return $user;
    }
}
