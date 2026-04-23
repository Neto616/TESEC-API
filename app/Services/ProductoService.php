<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Producto;
use Exception;
use Closure;

class ProductoService
{
    private $producto;
    public function __construct(Producto $producto)
    {
        $this->producto = $producto;
    }
    public static function existProducto($data, ?Closure $except = null)
    {
        $query = Producto::query()->where("nombre", $data["nombre"]);

        if ($except != null) {
            $except($query);
        }
        $producto = $query->first();
        if (!empty($producto)) {
            throw new Exception("Ya existe un producto con esos datos.", 409);
        }

        return $producto;
    }
    public static function crear($data)
    {
        return DB::transaction(function () use ($data) {
            self::existProducto($data);
            $producto = Producto::create($data);

            if ($data["imagen"] ?? false) {
                $ruta = $data["imagen"]->store("productos", "public");

                $producto->imagen()->create([
                    "ruta" => $ruta,
                    "nombre_original" => $data[
                        "imagen"
                    ]->getClientOriginalName(),
                ]);
            }

            if (isset($data["cantidad"])) {
                $producto->inventario()->create([
                    "cantidad" => $data["cantidad"],
                ]);
            }

            return $producto->load(["imagen", "inventario"]);
        });
    }
    public static function getAllReg(
        $relation = [],
        ?Closure $restricciones = null,
        $paginacion = 10,
        $busqueda = "",
    ) {
        $new_relacion = array_merge($relation, [
            "imagen",
            "proveedores",
            "inventario",
        ]);
        $query = Producto::query()
            ->when(!empty($relation), fn($q) => $q->with($relation))
            ->when(
                $busqueda,
                fn($q) => $q->where(function ($sub_q) use ($busqueda) {
                    $sub_q
                        ->where("nombre", "like", "%" . $busqueda . "%")
                        ->orWhere("sku", "like", "%" . $busqueda . "%")
                        ->orWhere("clave_sat", "like", "%" . $busqueda . "%")
                        ->orWhere("marca", "like", "%" . $busqueda . "%");
                    $sub_q->orWhereHas("proveedores", function ($q_prov) use (
                        $busqueda,
                    ) {
                        $q_prov->where("nombre", "like", "%" . $busqueda . "%");
                    });
                }),
            );

        if (!is_null($restricciones)) {
            $restricciones($query);
        }
        $producto = $query->with($new_relacion)->paginate($paginacion);
        return $producto;
    }
    public function editar($data)
    {
        return DB::transaction(function () use ($data) {
            $this->getById([]);
            $fn_except = function ($q) {
                $q->where("id", "<>", $this->producto->id)->where(
                    "estatus",
                    config("constants.common_status.activo"),
                );
            };
            self::existProducto($data, $fn_except);
            $this->producto->update($data);

            if ($data["imagen"] ?? false) {
                $ruta = $data["imagen"]->store("productos", "public");

                $this->producto->imagen()->create([
                    "ruta" => $ruta,
                    "nombre_original" => $data[
                        "imagen"
                    ]->getClientOriginalName(),
                ]);
            }
            if (isset($data["cantidad"])) {
                $this->producto
                    ->inventario()
                    ->updateOrCreate(
                        ["id_producto" => $this->producto->id],
                        ["cantidad" => $data["cantidad"]],
                    );
            }

            return $this->producto->load(["imagen", "inventario"]);
        });
    }
    public function eliminar()
    {
        return DB::transaction(function () {
            $this->getById([], function ($q) {
                return $q->where(
                    "estatus",
                    config("constants.common_status.activo"),
                );
            });
            $this->producto->update([
                "estatus" => config("constants.common_status.inactivo"),
            ]);
            return $this->producto->fresh(["inventario", "imagen"]);
        });
    }
    public function getById($relation = [], ?Closure $restricciones = null)
    {
        $new_relation = array_merge($relation, [
            "imagen",
            "proveedores",
            "inventario",
        ]);
        $query = Producto::query()
            ->where("id", $this->producto->id)
            ->with($new_relation);

        if (!is_null($restricciones)) {
            $restricciones($query);
        }
        $producto = $query->first();
        if (empty($producto)) {
            throw new Exception("No se encontro un producto con ese ID", 404);
        }
        return $producto;
    }
}
