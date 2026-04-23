<?php

namespace App\Http\Controllers;

use App\Http\Requests\AtributoRequest;
use App\Models\Atributo;
use App\Services\AtributoService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Exception;

class AtributoController extends Controller
{
    use ApiResponse;
    public function listar(Request $request) {
        $paginacion = $request->input("per_page", 10);
        if($paginacion > 100) $paginacion = 100;
        try {
            $atributos = AtributoService::getAllReg([], $paginacion);
        } catch (Exception $e) {
            return $this->error('Error al listar atributos', $e->getMessage(), $e->getCode());
        }
        return $this->success('Listado de atributos', $atributos);
    }

    public function ver(Atributo $atributo) {
        try {
            $service = new AtributoService($atributo);
            $data = $service->getById(null, [config('constants.common_status.activo')]);
        } catch (Exception $e) {
            return $this->error('Error al consultar atributo', $e->getMessage(), $e->getCode());
        }
        return $this->success('Atributo consultado', $data);
    }

    public function crear(AtributoRequest $request) {
        try {            
            $atributo = AtributoService::crear($request->validated());
        } catch (Exception $e) {
            return $this->error('Error al crear atributo', $e->getMessage(), $e->getCode());
        }
        return $this->success('Atributo creado exitosamente', $atributo, 201);
    }

    public function editar(AtributoRequest $request, Atributo $atributo) {
        try {
            $service = new AtributoService($atributo);
            $data = $service->editar($request->validated());
        } catch (Exception $e) {
            return $this->error('Error al editar atributo', $e->getMessage(), $e->getCode());
        }
        return $this->success('Atributo actualizado', $data);
    }

    public function eliminar(Atributo $atributo) {
        try {
            $service = new AtributoService($atributo);
            $data = $service->eliminar();
        } catch (Exception $e) {
            return $this->error('Error al eliminar atributo', $e->getMessage(), $e->getCode());
        }
        return $this->success('Atributo eliminado', $data);
    }
}