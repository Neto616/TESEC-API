<?php

namespace Tests\Feature;

use App\Models\Producto;
use App\Services\ProductoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductoTest extends TestCase
{
    use RefreshDatabase;
    private $data_test;
    private $data_test2;
    protected function setUp(): void
    {
        parent::setUp();
        $this->data_test = [
            "nombre" => "Papoi",
            "descripcion" => "Yeah perdonen kame hame ha, despues del tema de tetris sigue el dragon ball rap"
        ];
        $this->data_test2 = [
            "nombre"=> "C papu",
            "descripcion" => "Esto es una prueba c papu",
            "estatus" => config('constants.common_status.activo')
        ];
    }
    #[Test]
    public function crearTest(): void
    {
        $producto = ProductoService::crear($this->data_test);
        $this->assertInstanceOf(Producto::class, $producto);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Ya existe un producto con esos datos.');
        ProductoService::crear($this->data_test);
    }
    #[Test]
    public function editarTest(): void
    {
        $new_data = [
            'nombre'      => 'Producto 1',
            'descripcion' => '...'
        ];
        $producto  = ProductoService::crear($this->data_test);
        $producto2 = ProductoService::crear($this->data_test2);
        $service   = new ProductoService($producto);
        $service2  = new ProductoService($producto2);

        $new_data_producto = $service->editar($new_data);
        $this->assertEquals($new_data_producto->nombre, $new_data['nombre']);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Ya existe un producto con esos datos.');
        $service2->editar($new_data);
    }
    #[Test]
    public function eliminarTest(): void
    {
        $producto  = ProductoService::crear($this->data_test);
        $service   = new ProductoService($producto);
        
        $old_product = $service->eliminar();
        dump($old_product->toArray());
        $this->assertEquals($old_product->estatus, config('constants.common_status.inactivo'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No se encontro un producto con ese ID');
        $service->editar($this->data_test2);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No se encontro un producto con ese ID');
        $service->eliminar();
    }
    #[Test]
    public function obtenerTodosTest(): void
    {
        ProductoService::crear($this->data_test);
        ProductoService::crear($this->data_test2);

        $productos = ProductoService::getAllReg();
        $this->assertNotNull($productos, 'No se encontraron registros');
    }
    #[Test]
    public function obtenerRegistroTest(): void
    {
        $producto = ProductoService::crear($this->data_test);
        ProductoService::crear($this->data_test2);

        $service = new ProductoService($producto);
        $info_producto = $service->getById();
        $this->assertNotNull($info_producto, 'No se encontro regtistro con esos datos');
    }
}
