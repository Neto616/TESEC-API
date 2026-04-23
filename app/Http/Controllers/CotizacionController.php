<?php

namespace App\Http\Controllers;

use App\Http\Requests\CotizacionRequest;
use App\Models\Cotizacion;
use App\Services\CotizacionService;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;

class CotizacionController extends Controller
{
    use ApiResponse;
    public function __construct() {}

    public function listar(Request $request) {
        $busqueda = $request->query('busqueda');
        $paginacion = $request->input('per_page', 10);
        if($paginacion > 100) $paginacion = 100;
        $relacion = ['clientes.user'];

        try {
            $cotizaciones = CotizacionService::getAllReg($paginacion, $relacion, null, $busqueda);
            return $this->success('Listado de cotizaciones', $cotizaciones);
        } catch (Exception $e) {
            return $this->error('Error al listar los productos', $e->getMessage(), $e->getCode());
        }
    }
    public function ver(Cotizacion $cotizacion){
        try {
            $cotizacion->load(['partidas', 'partidas.productos.imagen', 'clientes.user']);
            return $this->success('Cotizacion consultada', $cotizacion);
        } catch (Exception $e) {
            return $this->error('Error al consultar la cotizacion', $e->getMessage(), 404);
        }
    }
    public function crear(CotizacionRequest $request){
        try {
            $cotizacion = CotizacionService::crear($request->validated());
            return $this->success('Cotizacion creada exitosamente', $cotizacion, 201);
        } catch (Exception $e) {
            return $this->error('Error al crear la cotizacion', $e->getMessage(), $e->getCode());
        }
    }
    public function editar(CotizacionRequest $request, Cotizacion $cotizacion){
        try {
            $service         = new CotizacionService($cotizacion);
            $cotizacion_data = $service->editar($request->validated());
            return $this->success('Cotizacion actualizado exitosamente', $cotizacion_data);
        } catch (Exception $e) {
            return $this->error('Error al actualactualizar la cotizacion', $e->getMessage(), $e->getCode());
        }
    }
    public function changeEstatus(CotizacionRequest $request, Cotizacion $cotizacion){ 
        try {
            $service         = new CotizacionService($cotizacion);
            $cotizacion_data = $service->changeEstatus($request->validated()['estatus']);
            return $this->success('Cotización actualizada exitosamente', $cotizacion_data);
        } catch (Exception $e) {
            return $this->error('Error al actualizar el estatus de la cotización', $e->getMessage(), $e->getCode());
        }
    }
}