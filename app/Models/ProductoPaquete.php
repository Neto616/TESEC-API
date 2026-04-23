<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoPaquete extends Model
{
    //
    protected $fillable = [
        "id_paquete",
        "id_variante",
        "cantidad",
    ];

    public function paquete()
    {
        return $this->belongsTo(Paquete::class, 'id_paquete');
    }
    public function variante(){
        return $this->belongsTo(Variante::class, 'id_variante');
    }
}
