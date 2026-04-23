<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Cliente;
use App\Models\User;
use Exception;
use Closure;

class ClienteService
{
    private $cliente;

    public function __construct(?Cliente $cliente)
    {
        $this->cliente = $cliente;
    }

    public static function crear($data)
    {
        return DB::transaction(function () use ($data) {
            $user_data = [
                "nombre" => $data["nombre"],
                "apellidos" => $data["apellidos"],
                "email" => $data["email"],
                "telefono" => $data["telefono"],
                "password" => bcrypt(Str::random(16)), // Password aleatorio
                "estatus" => config("constants.common_status.activo"),
            ];
            $usuario = UserService::registrar($user_data);
            $cliente = Cliente::create([
                "id_user" => $usuario->id,
            ]);

            return $cliente->load("user");
        });
    }

    public static function getAllReg(
        $perPage = 10,
        $relations = [],
        ?Closure $condicion,
    ) {
        $query = Cliente::query()->with($relations);

        if (!is_null($condicion)) {
            $condicion($query);
        }

        return $query->paginate($perPage);
    }

    public function eliminar()
    {
        return DB::transaction(function () {
            $cliente = $this->getById();
            $userService = new UserService($cliente->user);
            $userService->eliminar();

            return $cliente->fresh();
        });
    }

    private function getById()
    {
        $cliente = Cliente::query()->find($this->cliente->id);
        if (!$cliente) {
            throw new Exception("No se encontró el cliente", 404);
        }
        return $cliente;
    }
}
