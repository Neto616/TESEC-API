<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Imagen extends Model
{
    // Definimos la tabla si no sigue la convención plural (opcional)
    protected $table = 'imagenes';

    protected $fillable = [
        'ruta',
        'nombre_original',
        'imageable_id',
        'imageable_type'
    ];

    /**
     * Obtener el modelo al que pertenece la imagen (Producto, Variante, etc.)
     */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Accesorio para obtener la URL completa de la imagen
     * Esto es súper útil para Next.js
     */
    public function getUrlAttribute(): string
    {
        return asset(Storage::url($this->ruta));
    }

    // Esto hace que la URL aparezca siempre en el JSON automáticamente
    protected $appends = ['url'];
}