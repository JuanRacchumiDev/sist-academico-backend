<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DetalleParametro extends Model
{
    protected $table = "detalle_parametro";
    protected $primaryKey = "codigo";
    public $incrementing = true;

    protected $fillable = [
        'parametro_clase',
        'nombre',
        'nombre_url',
        'descripcion',
        'valor',
        'abreviatura',
        'longitud',
        'en_persona',
        'en_empresa',
        'compra',
        'venta',
        'user_crea',
        'user_actualiza',
        'user_elimina',
        'estado'
    ];

    protected $hidden = [
        'user_crea',
        'user_actualiza',
        'user_elimina',
        'created_at',
        'updated_at'
    ];

    /**
     * Obtener el parámetro vinculado a un detalle de parámetro
     * @return BelongsTo<Parametro, $this>
     */
    public function parametro(): BelongsTo
    {
        return $this->belongsTo(Parametro::class, 'clase', 'parametro_clase');
    }

    /**
     * The roles that belong to the DetalleParametro
     *
     * @return BelongsToMany<Persona, $this, Pivot>
     */
    public function personas(): BelongsToMany
    {
        return $this->belongsToMany(
            Persona::class,
            'grupo_persona',
            'codigo_detalle_parametro',
            'id_persona'
        )->withTimestamps();
    }
}
