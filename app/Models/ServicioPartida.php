<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicioPartida extends Model
{
    //
    protected $table = "servicios_partida";
    protected $fillable = [
        "id_servicio",
        "id_variante",
        "nombre",
        "cantidad",
        "precio_unitario",
        "precio_total",
    ];

    public function servicio(){
        return $this->belongsTo(Servicio::class, "id_servicio");
    }
    public function variante(){
        return $this->belongsTo(Variante::class, "id_variante");
    }
}
