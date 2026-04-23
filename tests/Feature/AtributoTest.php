<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\AtributoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class AtributoTest extends TestCase
{
    use RefreshDatabase;

    private $atributo_data;
    private $atributo_data2;
    private $atributo_data3;
    protected function setUp():void{
        parent::setUp();

        $this->atributo_data = [
            "titulo" => "prueba",
            "estatus"=> config('constants.common_status.activo'),
        ];

        $this->atributo_data2 = [
            "titulo" => "prueba2",
            "estatus"=> config('constants.common_status.activo'),
        ];

        $this->atributo_data3 = [
            "titulo" => "prueba3",
        ];
    }

    #[Test]
    public function crearAtributo(): void
    {
        $atributo = AtributoService::crear($this->atributo_data);
        $this->assertNotNull($atributo->id);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Ya existe un atributo con ese nombre');
        AtributoService::crear($this->atributo_data);
    }
    
    #[Test]
    public function actualizarAtributo(): void
    {
        $atributo = AtributoService::crear($this->atributo_data);
        $this->assertNotNull($atributo->id);
        $atributo2 = AtributoService::crear($this->atributo_data2);
        $this->assertNotNull($atributo2->id);
    
        $service = new AtributoService($atributo);

        $service->editar($this->atributo_data3);
        $this->assertNotNull($atributo->id);
        $this->assertEquals($atributo->titulo, $this->atributo_data3['titulo']);
        $this->assertNotEquals($atributo->titulo, $this->atributo_data['titulo']);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Ya existe un atributo con ese nombre');
        $service->editar($this->atributo_data2);
    }

    #[Test]
    public function eliminarAtributo(): void
    {
        $atributo = AtributoService::crear($this->atributo_data);
        $service = new AtributoService($atributo);
        $atributo_eliminado = $service->eliminar();
        $this->assertEquals(config('constants.common_status.inactivo'), $atributo_eliminado->estatus);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No se encontro un atributo');
        $service->eliminar();
    }

}
