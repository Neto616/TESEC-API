<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Producto extends Model
{
    use HasFactory;
    protected $fillable = [
        "nombre",
        "descripcion",
        "sku",
        "clave_sat",
        "marca",
        "link",
        "id_proveedor",
        "precio",
        "precio_publico",
        "estatus"
    ];

    public function getPublicPrice() {
        return $this->precio_publico;
    }

    public function getCost() {
        return $this->precio;
    }

    public function proveedores(){
        return $this->belongsTo(Proveedor::class, 'id_proveedor');
    }

    public function inventario()
    {
        return $this->hasOne(Inventario::class, 'id_producto');
    }
    public function imagen()
    {
        return $this->morphOne(Imagen::class, 'imageable');
    }
}
