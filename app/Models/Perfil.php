<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Perfil extends Model
{
    protected $table = 'perfiles';
    protected $fillable = ['perfil', 'estatus'];

    public function permisos(): BelongsToMany
    {
        // Relación muchos a muchos usando la tabla pivote de 3 vías
        return $this->belongsToMany(Permiso::class, 'perfil_permisos')
                    ->withPivot('seccion_id')
                    ->withTimestamps();
    }
}