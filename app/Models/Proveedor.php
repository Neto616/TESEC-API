<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Proveedor extends Model
{
    //
    protected $table = "proveedores";
    protected $fillable = [
        "nombre",
        "estatus",
        "correo_contacto",
        "telefono",
        "rfc",
        "direccion"
    ];

    public function imagen()
    {
        return $this->morphOne(Imagen::class, 'imageable');
    }

    public function variante(){
        return $this->hasMany(VarianteProveedores::class, 'id_proveedor');
    }
}
