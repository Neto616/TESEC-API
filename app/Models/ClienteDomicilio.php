<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClienteDomicilio extends Model
{
    //
    protected $table = "clientes_domcilios";
    protected $fillable = [
        "id_cliente",
        "pais",
        "estado",
        "ciudad",
        "calle",
        "colonia",
        "numero_exterior",
        "numero_interior",
        "codigo_postal",
        "estatus",
    ];

    public function cliente(){
        return $this->belongsTo(Cliente::class);
    }
}
