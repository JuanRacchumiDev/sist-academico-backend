<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Programa extends Model
{
    protected $table = 'programa';

    protected $guarded = ['id'];

    protected $fillable = [
        'id_segmento',
        'id_tipoprograma',
        'id_categoriaprograma',
        'id_institucion',

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
        return $this->belongsTo(DetalleParametro::class, 'id_segmento', 'codigo');
    }

    /**
     * Obtener el tipo de programa a un programa
     * @return BelongsTo<DetalleParametro, $this>
     */
    public function tipoPrograma(): BelongsTo
    {
        return $this->belongsTo(DetalleParametro::class, 'id_tipoprograma', 'codigo');
    }

    /**
     * Obtener la categoría de programa a un programa
     * @return BelongsTo<DetalleParametro, $this>
     */
    public function categoriaPrograma(): BelongsTo
    {
        return $this->belongsTo(DetalleParametro::class, 'id_categoriaprograma', 'codigo');
    }

    /**
     * Obtener la institución asociado a un programa
     * @return BelongsTo<Institucion, $this>
     */
    public function institucion(): BelongsTo
    {
        return $this->belongsTo(Institucion::class, 'id_institucion', 'id');
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
