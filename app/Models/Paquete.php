<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paquete extends Model
{
    //
    protected $fillable = [
        "nombre",
        "precio",
        "estatus",
    ];

    public function productos(){
        return $this->hasMany(ProductoPaquete::class, 'id_paquete');
    }
}
