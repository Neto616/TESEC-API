<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $fillable = [
        "id_producto",
        "isOnStorage",
        "cantidad",
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
    public function variante(){
        return $this->belongsTo(Variante::class, "id_variante");
    }
}
