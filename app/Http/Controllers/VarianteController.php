<?php

namespace App\Http\Controllers;

use App\Http\Requests\VarianteRequest;
use App\Models\Variante;
use App\Services\VarianteService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class VarianteController extends Controller
{
    //
    use ApiResponse;

    public function __construct() {}

    public function crear(VarianteRequest $request){
        try {
            $variante = VarianteService::crear($request->validated());
            return $this->success('Variante creada con exito', $variante, 201);
        } catch (\Exception $e) {
            return $this->error('Error al crear la variante', $e->getMessage(), $e->getCode());
        }
    }
    public function listar(Request $request){
        $paginacion = $request->input('per_page', 10);
        if($paginacion > 100) $paginacion = 100;
        try {
            $relation = ['producto', 'atributos', 'proveedores'];
            $variantes = VarianteService::getAllReg($relation, null, $paginacion);
        } catch (\Exception $e) {
            return $this->error('Error al listar las variantes', $e->getMessage(), $e->getCode());
        }
        return $this->success('Listado de variantes', $variantes);
    }
    public function obtener(Variante $variante){
        $service = new VarianteService($variante);
        try {
            $relacion = ['producto'];
            $data = $service->getById($relacion);
        } catch (\Exception $e) {
            return $this->error('Error al obtener variante', $e->getMessage(), $e->getCode());
        }
        return $this->success('Variante obtenida con exito', $data);
    }
    public function editar(VarianteRequest $request, Variante $variante){
        $service = new VarianteService($variante);
        try {   
            $new_variante = $service->editar($request->validated());
        } catch (\Exception $e) {
            $this->error('Error al editar la variante.', $e->getMessage(), $e->getCode());
        }
        return $this->success('Variante editada con exito.', $new_variante);
    }

    public function eliminar(Variante $variante){
        $service = new VarianteService($variante);
        try {
            $old_data = $service->eliminar();
        } catch (\Exception $e) {
            return $this->error('Error al eliminar variante', $e->getMessage(), $e->getCode());
        }
        return $this->success('Variante eliminar con exito.', $old_data);
    }
}
