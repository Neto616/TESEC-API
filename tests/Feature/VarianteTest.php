<?php

namespace Tests\Feature;

use App\Models\Producto;
use App\Services\VarianteService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class VarianteTest extends TestCase
{
    use RefreshDatabase;
    private $data_test;
    private $data_test2;
    protected function setUp(): void{
        parent::setUp();
        $producto = Producto::factory()->create();

        $this->data_test = [
            'id_producto' => $producto->id,
            'sku'         => '00001',
            'estatus'     => config('constants.common_status.activo'),
            'inventario'  => [
                'isOnStorage' => 1,
                'cantidad'    => 10
            ]
        ];

        $this->data_test2 = [
            'id_producto' => $producto->id,
            'sku'         => '00002',
        ];
    }
    #[Test]
    public function crearTest(): void
    {
        $variante = VarianteService::crear($this->data_test);
        $this->assertNotNull($variante);

        // $service = new VarianteService($variante);
        // dump($service->getById(['inventarios'])->toArray());

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Ya existe una variante con esos datos');
        VarianteService::crear($this->data_test);
    }
    #[Test]
    public function editarTest(): void
    {
        $new_value = [
            'sku' => '0003'
        ];
        $variante = VarianteService::crear($this->data_test);
        $variante2 = VarianteService::crear($this->data_test2);
        
        $service  = new VarianteService($variante);
        $service2 = new VarianteService($variante2);

        $new_data_variante = $service->editar($new_value);
        $this->assertEquals($new_data_variante->sku, $new_value['sku']);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Ya existe una variante con esos datos');
        $service2->editar($new_value);
    }
    #[Test]
    public function eliminarTest(): void
    {
        $variante = VarianteService::crear($this->data_test);
        $service = new VarianteService($variante);
        $old_value = $service->eliminar();
        $this->assertEquals($old_value->estatus, config('constants.common_status.inactivo'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No se encontro una variante con ese Id');
        $service->eliminar();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No se encontro una variante con ese Id');
        $service->editar($this->data_test);
    }
    #[Test]
    public function obtenerTodosTest(): void
    {
        VarianteService::crear($this->data_test);
        VarianteService::crear($this->data_test2);
        $variantes = VarianteService::getAllReg(['inventarios']);

        $this->assertNotNull($variantes);
    }
    #[Test]
    public function obtenerRegistro(): void
    {
        $variante  = VarianteService::crear($this->data_test);
        $variante2 = VarianteService::crear($this->data_test2);

        $service = new VarianteService($variante);
        $service2 = new VarianteService($variante2);

        $this->assertNotNull($service->getById());
        $this->assertNotNull($service2->getById());
    }
}
