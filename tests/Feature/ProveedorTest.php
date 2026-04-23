<?php

namespace Tests\Feature;

use App\Models\Proveedor;
use App\Services\ProveedorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProveedorTest extends TestCase
{
    use RefreshDatabase;
    private $data_test;
    private $data_test2;
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->data_test = [
            "nombre" => 'Prueba 1',
            "estatus" => config('constants.common_status.activo'),
        ];

        $this->data_test2 = [
            "nombre" => "Prueba C papu"
        ];
    }
    #[Test]
    public function crearTest(): void
    {
        $proveedor = ProveedorService::crear($this->data_test);
        $this->assertInstanceOf(Proveedor::class, $proveedor);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Ya existe un proveedor con esos datos");
        ProveedorService::crear($this->data_test);
    }
    #[Test]
    public function editarTest(): void
    {
        $new_name   = 'Vamos cerrando el papoi';
        $proveedor  = ProveedorService::crear($this->data_test);
        $proveedor2 = ProveedorService::crear($this->data_test2);

        $service  = new ProveedorService($proveedor);
        $service2 = new ProveedorService($proveedor2);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Ya existe un proveedor con esos datos');
        $service->editar($this->data_test2);

        $new_data_atributo = $service2->editar(['nombre' => $new_name]);
        $this->assertEquals($new_data_atributo->nombre, $new_name, 'Los nombre no coinciden');
    }
    #[Test]
    public function eliminarTest(): void
    {
        $proveedor = ProveedorService::crear($this->data_test);

        $service = new ProveedorService($proveedor);
        
        $proveedor_eliminar = $service->eliminar();
        $this->assertEquals($proveedor_eliminar->estatus, config('constants.common_status.inactivo'), 'El proveedor no se elimino');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No se encontro un proveedor con ese ID');
        $service->editar($this->data_test2);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No se encontro un proveedor con ese ID');
        $service->eliminar();
    }
    #[Test]
    public function obtenerRegistrosTest(): void
    {
        ProveedorService::crear($this->data_test); 
        ProveedorService::crear($this->data_test2);
        
        $proveedores = ProveedorService::getAll();
        $this->assertNotNull($proveedores);
    }
    #[Test]
    public function obtenerRegistroTest(): void
    {
        $proveedor = ProveedorService::crear($this->data_test);
        $service = new ProveedorService($proveedor);

        $info_proveedor = $service->getById();
        $this->assertNotNull($info_proveedor);
    }
}
