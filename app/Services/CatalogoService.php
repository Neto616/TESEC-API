<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Models\Proveedor;
use App\Models\Cliente;

class CatalogoService
{
    public function __construct() {}

    public function getPerfiles(): Collection
    {
        return DB::table("perfiles")
            ->where("estatus", config("constants.common_status.activo"))
            ->select("id as value", "perfil as label")
            ->get();
    }
    public function getProveedores(): Collection
    {
        return Proveedor::query()
            ->where("estatus", config("constants.common_status.activo"))
            ->select("id as value", "nombre as label")
            ->get();
    }
    public function getClientes(): Collection
    {
        return Cliente::with("user")
            ->whereHas("user")
            ->get()
            ->map(function ($cliente) {
                return [
                    "value" => $cliente->id,
                    "label" => "{$cliente->user->nombre} {$cliente->user->apellidos}",
                ];
            });
    }
}
