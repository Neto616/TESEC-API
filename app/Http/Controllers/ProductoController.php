<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductoRequest;
use App\Models\Producto;
use App\Services\ProductoService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class ProductoController extends Controller
{
    use ApiResponse;
    public function __construct() {}

    public function listar(Request $request){
        $busqueda = $request->query('busqueda');
        $paginacion = $request->input("per_page", 10);
        if($paginacion > 100) $paginacion = 100;
        $relacion = ['inventario', 'imagen'];

        try {
            $productos = ProductoService::getAllReg($relacion, null, $paginacion, $busqueda);
        } catch (\Exception $e) {
            return $this->error('Error al listar los productos', $e->getMessage(), $e->getCode());
        }
        return $this->success('Listado de productos', $productos);
    }
    public function crear(ProductoRequest $request){
        $validated = $request->validated();
        try {
            $producto = ProductoService::crear($validated);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error('Error al crear producto', $e->getMessage(), $e->getCode());
        }
        return $this->success('Producto creado exitosamente', $producto, 201);
    }
    public function ver(Producto $producto){
        $relacion = ['inventario', 'imagen'];
        try {
            $service = new ProductoService($producto);
            $producto_consulta = $service->getById($relacion);
        } catch (\Exception $e) {
            return $this->error('Error al consultar producto', $e->getMessage(), $e->getCode());
        }
        return $this->success('Producto consultado exitosamente', $producto_consulta);
    }
    public function editar(ProductoRequest $request, Producto $producto){
        $validated = $request->validated();
        try {
            $service = new ProductoService($producto);
            $producto_data = $service->editar($validated);
        } catch (\Exception $e) {
            return $this->error('Error al editar un producto', $e->getMessage(), $e->getCode());
        }
        return $this->success('Producto actualizado exitosamente', $producto_data);
    }
    public function eliminar(Producto $producto){
        try {
            $service = new ProductoService($producto);
            $producto_eliminado = $service->eliminar();
        } catch (\Exception $e) {
            return $this->error('Error al eliminar un producto', $e->getMessage(), $e->getCode());
        }
        return $this->success('Producto eliminado exitosamente', $producto_eliminado);
    }
}
