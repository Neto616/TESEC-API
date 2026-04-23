<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Proveedor;
use Closure;
use Exception;

class ProveedorService
{
    private $proveedor;
    public function __construct(Proveedor $proveedor)
    {
        $this->proveedor = $proveedor;
    }
    public static function getAll(
        $relation = [],
        ?Closure $except = null,
        $paginacion = 10,
    ) {
        $query = Proveedor::query();

        if (!empty($relation)) {
            $query->with($relation);
        }
        if ($except != null) {
            $except($query);
        }

        return $query->paginate($paginacion);
    }
    public static function existProveedor($data, ?Closure $except = null)
    {
        $query = Proveedor::query()
            ->where("nombre", $data["nombre"])
            ->where("estatus", config("constants.common_status.activo"));

        if ($except != null) {
            $except($query);
        }
        $proveedor = $query->first();
        if (!empty($proveedor)) {
            throw new Exception("Ya existe un proveedor con esos datos", 409);
        }
    }
    public static function crear($data)
    {
        return DB::transaction(function () use ($data) {
            self::existProveedor($data);
            $proveedor = Proveedor::create($data);

            if ($data["logo"] ?? false) {
                $ruta = $data["logo"]->store("logos", "public");

                $proveedor->imagen()->create([
                    "ruta" => $ruta,
                    "nombre_original" => $data["logo"]->getClientOriginalName(),
                ]);
            }
            return $proveedor->load("imagen");
        });
    }
    public function editar($data)
    {
        return DB::transaction(function () use ($data) {
            $fn_except = function ($q) {
                $q->where("estatus", config("constants.common_status.activo"));
            };
            $this->getById(null, $fn_except);
            $fn_except = function ($q) {
                $q->where("id", "<>", $this->proveedor->id);
            };

            $this->existProveedor($data, $fn_except);

            $this->proveedor->update($data);
            return $this->proveedor->fresh();
        });
    }
    public function eliminar()
    {
        return DB::transaction(function () {
            $fn_except = function ($q) {
                $q->where("estatus", config("constants.common_status.activo"));
            };
            $this->getById(null, $fn_except);
            $this->proveedor->update([
                "estatus" => config("constants.common_status.inactivo"),
            ]);
            return $this->proveedor->fresh();
        });
    }
    public function getById($relations = null, ?Closure $condiciones = null)
    {
        $query = Proveedor::query()
            ->where("id", $this->proveedor->id)
            ->when(!empty($relations), fn($q) => $q->with($relations));

        if (!is_null($condiciones)) {
            $condiciones($query);
        }
        $proveedor = $query->first();
        if (empty($proveedor)) {
            throw new Exception("No se encontro un proveedor con ese ID", 404);
        }
        return $proveedor;
    }
}
