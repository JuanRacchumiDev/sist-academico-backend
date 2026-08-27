<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CuestionarioPersona extends Pivot
{
    protected $table = "cuestionario_persona";

    public $incrementing = true;

    protected $fillable = [
        "id_cuestionario",
        "id_persona",
        "numero_intento",
        "fecha_inicio",
        "fecha_fin",
        "puntaje_total",
        "estado_intento",
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
        'estado' => 'boolean'
    ];

    public function cuestionario(): BelongsTo
    {
        return $this->belongsTo(Cuestionario::class, "id_cuestionario", "id");
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, "id_persona", "id");
    }

    /**
     * Relación con las respuestas
     */
    public function respuestas(): HasMany
    {
        return $this->hasMany(CuestionarioPersona::class, "id_cuestionario_persona", "id");
    }
}
