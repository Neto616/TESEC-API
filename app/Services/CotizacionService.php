<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\CotizacionPartida;
use Illuminate\Support\Str;
use App\Models\Cotizacion;
use App\Models\Producto;
use Closure;

class CotizacionService
{
    private Cotizacion $cotizacion;
    public function __construct(Cotizacion $cotizacion)
    {
        $this->cotizacion = $cotizacion;
    }

    public static function getAllReg(
        $perPage = 10,
        $relations = [],
        ?Closure $condiciones = null,
        ?string $search = null,
    ) {
        $query = Cotizacion::query()->with($relations);

        if (!is_null($search)) {
            $query->where(function ($sub_q) use ($search) {
                // Buscamos por UUID en la tabla cotizaciones
                $sub_q
                    ->where("uuid", "like", "%{$search}%")
                    // O buscamos a través de las relaciones
                    ->orWhereHas("clientes", function ($q_prov) use ($search) {
                        $q_prov->whereHas("user", function ($q_user) use (
                            $search,
                        ) {
                            $q_user->where(function ($filter) use ($search) {
                                $filter
                                    ->where("nombre", "like", "%{$search}%")
                                    ->orWhere(
                                        "apellidos",
                                        "like",
                                        "%{$search}%",
                                    );
                            });
                        });
                    });
            });
        }

        if (!is_null($condiciones)) {
            $condiciones($query);
        }

        return $query->orderBy("created_at", "desc")->paginate($perPage);
    }
    public static function crear($data)
    {
        return DB::transaction(function () use ($data) {
            $cotizacion_data = [
                "uuid" => Str::uuid(),
                "titulo" => !empty($data["titulo"])
                    ? $data["titulo"]
                    : "Cotizacion",
                "consideraciones" => $data["consideraciones"],
                "useIVA" => $data["useIVA"],
                "useISR" => $data["useISR"],
                "id_cliente" => $data["id_cliente"],
                "estatus" => config("constants.price_status.en_proceso"),
            ];
            $cotizacion = Cotizacion::create($cotizacion_data);

            if (isset($data["productos"])) {
                foreach ($data["productos"] as $productos) {
                    $id_producto = $productos["id"];
                    $partida_data = [
                        "id_cotizacion" => $cotizacion->id,
                        "id_productos" => $id_producto,
                        "cantidad" => $productos["cantidad"],
                        "total" =>
                            $productos["cantidad"] *
                            Producto::find($id_producto)->getPublicPrice(),
                    ];

                    CotizacionPartida::create($partida_data);
                }
            }

            return $cotizacion->fresh(["clientes", "partidas"]);
        });
    }
    public function obtener($relations = [], ?Closure $condiciones = null)
    {
        $query = $this->cotizacion
            ->query()
            ->when(!empty($relations), fn($q) => $q->with($relations));

        if ($condiciones != null) {
            $condiciones($query);
        }
        return $this->cotizacion->first();
    }
    public function editar($data)
    {
        return DB::transaction(function () use ($data) {
            $cotizacion_data = [
                "titulo" => $data["titulo"] ?? $this->cotizacion->titulo,
                "id_cliente" => $data["id_cliente"],
            ];

            $this->cotizacion->update($cotizacion_data);

            if (isset($data["productos"])) {
                $this->cotizacion->partidas()->delete();

                foreach ($data["productos"] as $productos) {
                    $partida_data = [
                        "id_cotizacion" => $this->cotizacion->id,
                        "id_productos" => $productos["id"],
                        "cantidad" => $productos["cantidad"],
                        "total" => $productos["total"],
                    ];

                    $this->cotizacion->partidas->create($partida_data);
                }
            }

            return $this->cotizacion->fresh(["partidas", "clientes"]);
        });
    }
    public function changeEstatus($estatus)
    {
        return DB::transaction(function () use ($estatus) {
            $this->cotizacion->update([
                "estatus" => $estatus,
            ]);

            return $this->cotizacion->fresh();
        });
    }
}
