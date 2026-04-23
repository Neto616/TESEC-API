<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteRequest;
use App\Models\Cliente;
use App\Services\ClienteService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Exception;

class ClienteController extends Controller
{
    use ApiResponse;
    public function listar(Request $request)
    {
        $paginacion = $request->input("per_page", 10);
        if ($paginacion > 100) {
            $paginacion = 100;
        }
        try {
            $clientes = ClienteService::getAllReg($paginacion, ["user"], null);
            return $this->success("Listado de clientes", $clientes);
        } catch (Exception $e) {
            return $this->error("Error al listar clientes", $e->getMessage());
        }
    }
    public function ver(Cliente $cliente)
    {
        try {
            $cliente->load("user");
        } catch (Exception $e) {
            return $this->error(
                "Error al consultar cliente",
                $e->getMessage(),
                404,
            );
        }
        return $this->success("Cliente consultado", $cliente);
    }
    public function crear(ClienteRequest $request)
    {
        try {
            $cliente = ClienteService::crear($request->validated());
        } catch (Exception $e) {
            return $this->error(
                "Error al crear cliente",
                $e->getMessage(),
                $e->getCode() ?: 500,
            );
        }
        return $this->success("Cliente creado exitosamente", $cliente, 201);
    }
    public function eliminar(Cliente $cliente)
    {
        try {
            $service = new ClienteService($cliente);
            $data = $service->eliminar();
        } catch (Exception $e) {
            return $this->error(
                "Error al eliminar cliente",
                $e->getMessage(),
                $e->getCode() ?: 500,
            );
        }
        return $this->success("Cliente eliminado correctamente", $data);
    }
}
