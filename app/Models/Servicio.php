<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    //
    protected $fillable = [
        "id_cliente",
    ];

    public function cliente(){
        return $this->belongsTo(Cliente::class, "id_cliente");
    }

    public function partidas(){
        return $this->hasMany(ServicioPartida::class,"id_cliente");
    }

    public function servicioTrabajadores(){
        return $this->hasMany(ServicioTrabajador::class,"id_servicio");
    }

    public function observacion(){
        return $this->morphMany(Observacion::class,"modulo");
    }
}
