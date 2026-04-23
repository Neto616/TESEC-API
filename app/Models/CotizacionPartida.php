<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CotizacionPartida extends Model
{
    //
    protected $table = 'cotizacion_partida';
    protected $fillable = [
        'id_cotizacion',
        'id_productos',
        'cantidad',
        'total'
    ];

    public function cotizacion(){
        return  $this->belongsTo(Cotizacion::class, 'id_cotizacion');
    }
    public function productos(){
        return $this->belongsTo(Producto::class, 'id_productos');
    }
}
