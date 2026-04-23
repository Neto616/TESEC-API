<?php

namespace App\Http\Controllers;

use App\Services\CatalogoService;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    use ApiResponse;
    private $catalogo_service;
    public function __construct() {
        $this->catalogo_service = new CatalogoService();
    }
    public function getPerfiles(){
        try {
            $permisos = $this->catalogo_service->getPerfiles();

            return $this->success('Lista de permisos', $permisos);
        } catch (Exception $e) {
            return $this->error('Error al listar perfiles', $e->getMessage(), $e->getCode());
        }
    }
    public function getProveedores(){
        try {
            $proveedores = $this->catalogo_service->getProveedores();

            return $this->success('Lista de proveedores', $proveedores);
        } catch (Exception $e) {
            return $this->error('Error al listar proveedores', $e->getMessage(), $e->getCode());
        }
    }
    public function getClientes(){
        try {
            $clientes = $this->catalogo_service->getClientes();

            return $this->success('Lista de clientes', $clientes);
        } catch (Exception $e) {
            return $this->error('Error al listar clientes', $e->getMessage(), $e->getCode());
        }
    }
}
