<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Atributo;
use Exception;
use Closure;

class AtributoService
{
    private $atributo;
    public function __construct(Atributo $atributo)
    {
        $this->atributo = $atributo;
    }
    public static function existAtributo($data, ?int $except = null)
    {
        $atributo = Atributo::query()
            ->where("titulo", $data["titulo"])
            ->when(!is_null($except), fn($q) => $q->where("id", "<>", $except))
            ->exists();
        if ($atributo) {
            throw new Exception("Ya existe un atributo con ese nombre");
        }
    }
    public static function crear($data)
    {
        return DB::transaction(function () use ($data) {
            self::existAtributo($data);

            $new_atributo = Atributo::create($data);

            return $new_atributo;
        });
    }
    public static function getAllReg($relation = [], $per_page = 10)
    {
        $query = Atributo::query()->where(
            "estatus",
            config("constants.common_status.activo"),
        );
        if (!empty($relation)) {
            $query->with($relation);
        }
        return $query->paginate($per_page);
    }
    public function getById(?Closure $condiciones = null, $estatus = [])
    {
        $query = Atributo::query()
            ->where("id", $this->atributo->id)
            ->when($estatus, fn($q) => $q->whereIn("estatus", $estatus));
        if (!is_null($condiciones)) {
            $condiciones($query);
        }
        $atributo = $query->first();
        if (empty($atributo)) {
            throw new Exception("No se encontro un atributo");
        }

        return $atributo;
    }
    public function setValues(array $values)
    {
        return DB::transaction(function () use ($values) {
            $this->getById(null, [config("constants.common_status.activo")]);
            $resultados = [];
            foreach ($values as $item) {
                $resultados[] = $this->atributo
                    ->variantesAtributo()
                    ->firstOrCreate([
                        "id_variante" => $item["id_variante"],
                        "id_atributo" => $this->atributo->id,
                        "valor" => $item["valor"],
                    ]);
            }
            return $resultados;
        });
    }
    public function editar($data)
    {
        return DB::transaction(function () use ($data) {
            $this->getById(null, [config("constants.common_status.activo")]);

            if (isset($data["titulo"])) {
                self::existAtributo($data, $this->atributo->id);
            }

            $this->atributo->update($data);
            return $this->atributo->fresh();
        });
    }
    public function eliminar()
    {
        return DB::transaction(function () {
            $this->getById(null, [config("constants.common_status.activo")]);
            $this->atributo->update([
                "estatus" => config("constants.common_status.inactivo"),
            ]);
            return $this->atributo->fresh();
        });
    }
}
