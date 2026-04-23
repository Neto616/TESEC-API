<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    //
    protected $table = "cotizaciones";

    protected $fillable = [
        "uuid",
        "titulo",
        "consideraciones",
        "useIVA",
        "useISR",
        "id_cliente",
        "estatus",
    ];

    protected $casts = [
        "useIVA" => "boolean",
        "useISR" => "boolean",
    ];

    public function partidas()
    {
        return $this->hasMany(CotizacionPartida::class, "id_cotizacion");
    }

    public function clientes()
    {
        return $this->belongsTo(Cliente::class, "id_cliente");
    }
}
