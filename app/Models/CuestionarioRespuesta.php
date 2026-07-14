<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CuestionarioRespuesta extends Pivot
{
    protected $table = "cuestionario_respuesta";

    public $incrementing = true;

    protected $fillable = [
        "id_cuestionario_persona",
        "id_pregunta",
        "id_pregunta_opcion",
        "respuesta_texto",
        "puntaje_obtenido",
        "es_correcta",
        "user_crea",
        "user_actualiza",
        "user_elimina",
        "estado"
    ];

    protected $hidden = [
        'user_crea',
        'user_actualiza',
        'user_elimina',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'es_correcta' => 'boolean',
        'estado' => 'boolean'
    ];

    /**
     * Relación con el intento/persona asociado a esta respuesta
     */
    public function cuestionarioPersona(): BelongsTo
    {
        return $this->belongsTo(CuestionarioPersona::class, "id_cuestionario_persona", "id");
    }

    /**
     * Relación con la pregunta contestada
     */
    public function pregunta(): BelongsTo
    {
        return $this->belongsTo(Pregunta::class, "id_pregunta", "id");
    }

    /**
     * Relación con la opción marcada (para tipo RADIO o CHECKBOX)
     */
    public function opcion(): BelongsTo
    {
        return $this->belongsTo(PreguntaOpcion::class, "id_pregunta_opcion", "id");
    }
}
