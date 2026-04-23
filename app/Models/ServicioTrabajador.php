<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicioTrabajador extends Model
{
    //
    protected $table = "trabajadores_servicio";
    protected $fillable = [
        "id_servicio",
        "id_usuario",
    ];

    public function servicio(){
        return $this->belongsTo(Servicio::class,"id_servicio");
    }

    public function usuario(){
        return $this->belongsTo(User::class, "id_usuario");
    }
}
