<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Observacion extends Model
{
    protected $table = 'observaciones';

    protected $fillable = [
        'observaciones',
        'modulo_id',
        'modulo_type'
    ];

    /**
     * Obtener el modelo padre (Servicio, u otro que use observaciones).
     */
    public function modulo()
    {
        return $this->morphTo();
    }
}
