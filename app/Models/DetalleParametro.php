<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'visible',
        'fecha_crea',
        'fecha_actualiza',
        'fecha_elimina',
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
     * @return BelongsToMany<Persona, $this>
     */
    public function personas(): BelongsToMany
    {
        return $this->belongsToMany(
            Persona::class,
            'grupo_persona',
            'codigo_grupo',
            'id_persona'
        )->withTimestamps();
    }

    /**
     * Programas asociados por tipo de programa
     * @return HasMany<Programa, $this>
     */
    public function programasPorTipo(): HasMany
    {
        return $this->hasMany(Programa::class, 'codigo_tipoprograma', 'codigo');
    }

    /**
     * Programas asociados por categoría de programa
     * @return HasMany<Programa, $this>
     */
    public function programasPorCategoria(): HasMany
    {
        return $this->hasMany(Programa::class, 'codigo_categoriaprograma', 'codigo');
    }

    /**
     * Programas asociados por segmento
     * @return HasMany<Programa, $this>
     */
    public function programasPorSegmento(): HasMany
    {
        return $this->hasMany(Programa::class, 'codigo_segmento', 'codigo');
    }
}
