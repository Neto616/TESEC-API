<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Inventario;

class InventarioService
{
    private $inventario;
    public function __construct(?Inventario $inventario)
    {
        $this->inventario = $inventario;
    }
    public static function existInventario($data)
    {
        $query = Inventario::query()->where(
            "id_variante",
            $data["id_variante"],
        );

        return $query->first();
    }
    public function registrarEntrada($data)
    {
        return DB::transaction(function () use ($data) {
            $inventario = self::existInventario($data);
            if (empty($inventario)) {
                $inventario = Inventario::create($data);
            } else {
                $inventario->update($data);
            }
            return $inventario;
        });
    }
}
