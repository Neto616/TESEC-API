<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Variante;
use Exception;
use Closure;

class VarianteService
{
    private $variante;

    public function __construct(Variante $variante)
    {
        $this->variante = $variante;
    }
    public static function getAllReg(
        $relation = [],
        ?Closure $exception = null,
        $paginacion = 10,
    ) {
        $query = Variante::query()->when(
            !empty($relation),
            fn($q) => $q->with($relation),
        );

        if ($exception != null) {
            $exception($query);
        }
        return $query->paginate($paginacion);
    }
    public static function existVariante($data, ?Closure $exception = null)
    {
        $query = Variante::query()->where("sku", "=", $data["sku"]);

        if ($exception != null) {
            $exception($query);
        }
        $variante = $query->first();
        if (!empty($variante)) {
            throw new Exception("Ya existe una variante con esos datos", 409);
        }
    }
    private static function linkProveedor(Variante $variante, $data_proveedor)
    {
        return DB::transaction(function () use ($variante, $data_proveedor) {
            $data = [
                $data_proveedor["id_proveedor"] => [
                    "precio_publico" => $data_proveedor["precio_publico"],
                    "precio" => $data_proveedor["precio"],
                ],
            ];

            $variante->proveedores()->syncWithoutDetaching($data);
        });
    }
    private static function linkAtributos(Variante $variante, $data_atributos)
    {
        return DB::transaction(function () use ($variante, $data_atributos) {
            $data = [];

            foreach ($data_atributos as $atributo) {
                $data[$atributo["id"]] = ["valor" => $atributo["valor"]];
            }

            $variante->atributos()->sync($data);
        });
    }
    public static function crear($data)
    {
        return DB::transaction(function () use ($data) {
            self::existVariante($data);
            $variante = Variante::create($data);

            if ($data["atributos"] ?? false) {
                self::linkAtributos($variante, $data["atributos"]);
            }

            if ($data["proveedor"] ?? false) {
                $proveedor_data = [
                    "id_proveedor" => $data["proveedor"]["id"],
                    "precio_publico" => $data["proveedor"]["precio_publico"],
                    "precio" => $data["proveedor"]["precio"],
                ];

                self::linkProveedor($variante, $proveedor_data);
            }

            if ($data["inventario"] ?? false) {
                $inventario_data = [
                    "id_variante" => $variante->id,
                    "isOnStorage" => $data["inventario"]["isOnStorage"],
                    "cantidad" => $data["inventario"]["isOnStorage"]
                        ? $data["inventario"]["cantidad"]
                        : 0,
                ];
                $service = new InventarioService();
                $service->registrarEntrada($inventario_data);
            }

            return $variante->load([
                "varianteAtributos",
                "varianteProveedores",
            ]);
        });
    }
    public function editar($data)
    {
        return DB::transaction(function () use ($data) {
            $fn_except = function ($q) {
                $q->where("estatus", config("constants.common_status.activo"));
            };
            $this->getById([], $fn_except);
            $except = function ($q) {
                $q->where("id", "<>", $this->variante->id);
            };
            self::existVariante($data, $except);
            $this->variante->update($data);
            return $this->variante->fresh();
        });
    }
    public function getById($relation = [], ?Closure $condicion = null)
    {
        $query = Variante::query()
            ->where("id", $this->variante->id)
            ->when(!empty($relation), fn($q) => $q->with($relation));

        if ($condicion != null) {
            $condicion($query);
        }

        $variante = $query->first();

        if (empty($variante)) {
            throw new Exception("No se encontro una variante con ese Id", 404);
        }
        return $variante;
    }
    public function eliminar()
    {
        return DB::transaction(function () {
            $fn_except = function ($q) {
                $q->where("estatus", config("constants.common_status.activo"));
            };
            $this->getById([], $fn_except);
            $this->variante->update([
                "estatus" => config("constants.common_status.inactivo"),
            ]);
            $this->variante->inventarios()->update(["cantidad" => 0]);

            return $this->variante->fresh();
        });
    }
}
