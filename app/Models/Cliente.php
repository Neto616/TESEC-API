<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    //
    protected $fillable = [
        "id_user"
    ];

    public function user() {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function domicilios(){
        return $this->hasMany(ClienteDomicilio::class);
    }

    public function servicios(){
        return $this->hasMany(Servicio::class, "id_cliente");
    }
}
