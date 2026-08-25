<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Programa extends Model
{
    protected $table = 'programa';

    protected $guarded = ['id'];

    protected $fillable = [
        'codigo_segmento',
        'codigo_tipoprograma',
        'codigo_categoriaprograma',
        'id_sucursal',

        'codigo_old',
        'sigla',
        'titulo',
        'titulo_url',
        'descripcion',
        'temario',
        'fecha_inicio',
        'fecha_final',
        'duracion',
        'horas_academicas',
        'numero_modulos',
        'creditos',
        'plan',
        'modalidad',
        'capacidad_minima',
        'capacidad_maxima',
        'cantidad_inscritos',
        'precio_modulo',
        'is_vigente',
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

    protected $casts = [
        'is_visible' => 'boolean',
        'estado' => 'boolean'
    ];

    /**
     * Obtener el segmento a un programa
     * @return BelongsTo<DetalleParametro, $this>
     */
    public function segmento(): BelongsTo
    {
        return $this->belongsTo(DetalleParametro::class, 'codigo_segmento', 'codigo');
    }

    /**
     * Obtener el tipo de programa a un programa
     * @return BelongsTo<DetalleParametro, $this>
     */
    public function tipoPrograma(): BelongsTo
    {
        return $this->belongsTo(DetalleParametro::class, 'codigo_tipoprograma', 'codigo');
    }

    /**
     * Obtener la categoría de programa a un programa
     * @return BelongsTo<DetalleParametro, $this>
     */
    public function categoriaPrograma(): BelongsTo
    {
        return $this->belongsTo(DetalleParametro::class, 'codigo_categoriaprograma', 'codigo');
    }

    /**
     * Obtener la sucursal asociada a un programa
     * @return BelongsTo<DetalleParametro, $this>
     */
    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(DetalleParametro::class, 'id_sucursal', 'codigo');
    }

    public function detalleMatricula()
    {
        return $this->hasMany(DetalleMatricula::class, 'id_programa', 'id');
    }

    public function detalleModulos()
    {
        return $this->hasMany(Modulo::class, 'id_programa', 'id');
    }

    public function adjuntos()
    {
        return $this->hasMany(Adjunto::class, 'id_programa', 'id');
    }
}
