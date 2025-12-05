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
        'codigo_old',
        'sigla',
        'nombre',
        'nombre_url',
        'descripcion',
        'duracion',
        'fecha_inicio',
        'fecha_final',
        'modulos',
        'creditos',
        'plan',
        'modalidad',
        'temario',
        'capacidad_minima',
        'capacidad_maxima',
        'cantidad_inscritos',
        'valor_cuota',
        'user_crea',
        'user_actualiza',
        'user_elimina',
        'is_vigente',
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

    public function detalleMatricula()
    {
        return $this->hasMany(DetalleMatricula::class, 'id_programa', 'id');
    }
}
