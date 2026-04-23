<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProveedorRequest;
use App\Models\Proveedor;
use App\Services\ProveedorService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Exception; 
class ProveedorController extends Controller
{
    use ApiResponse;
    public function listar(Request $request) 
    {
        try {
            $proveedores = ProveedorService::getAll(['imagen'], null, $request->get('per_page', 10));
        } catch (Exception $e) {
            return $this->error('Error al listar proveedores', $e->getMessage(), $e->getCode() ?: 500);
        }
        return $this->success('Listado de proveedores', $proveedores);
    }
    public function ver(Proveedor $proveedor) 
    {
        try {
            $service = new ProveedorService($proveedor);
            $fn_status = function($q){ 
                $q->where('estatus', config('constants.common_status.activo')); 
            };
            
            $data = $service->getById(['imagen'], $fn_status);

        } catch (Exception $e) {
            return $this->error('Error al consultar proveedor', $e->getMessage(), $e->getCode() ?: 404);
        }
        return $this->success('Proveedor consultado exitosamente', $data);
    }
    public function crear(ProveedorRequest $request) 
    {
        try {            
            $proveedor = ProveedorService::crear($request->all());
        } catch (Exception $e) {
            return $this->error('Error al crear proveedor', $e->getMessage(), $e->getCode() ?: 500);
        }
        return $this->success('Proveedor creado exitosamente', $proveedor, 201);
    }
    public function editar(ProveedorRequest $request, Proveedor $proveedor) 
    {
        try {
            $service = new ProveedorService($proveedor);
            $data = $service->editar($request->all());

        } catch (Exception $e) {
            return $this->error('Error al editar proveedor', $e->getMessage(), $e->getCode() ?: 500);
        }
        return $this->success('Proveedor actualizado exitosamente', $data);
    }
    public function eliminar(Proveedor $proveedor) 
    {
        try {
            $service = new ProveedorService($proveedor);
            $data = $service->eliminar();
        } catch (Exception $e) {
            return $this->error('Error al eliminar proveedor', $e->getMessage(), $e->getCode() ?: 500);
        }
        return $this->success('Proveedor eliminado exitosamente', $data);
    }
}