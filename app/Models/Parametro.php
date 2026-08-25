<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Parametro extends Model
{
    protected $table = "parametro";
    protected $primaryKey = "clase";
    public $incrementing = false;

    protected $fillable = [
        "clase",
        "nombre",
        "nombre_url",
        "descripcion",
        "fecha_crea",
        "fecha_actualiza",
        "fecha_elimina",
        "user_crea",
        "user_actualiza",
        "user_elimina",
        "estado"
    ];

    /**
     * Obtener el detalle de un parámetro
     * @return HasMany<DetalleParametro, $this>
     */
    public function detalle(): HasMany
    {
        return $this->hasMany(DetalleParametro::class, 'parametro_clase', 'clase');
    }
}
