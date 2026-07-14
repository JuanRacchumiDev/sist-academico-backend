<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PreguntaOpcion extends Model
{
    protected $table = "pregunta_opcion";

    protected $guarded = ["id"];

    protected $fillable = [
        "id_pregunta",
        "texto_opcion",
        "es_correcta",
        "orden",
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

    public function pregunta(): BelongsTo
    {
        return $this->belongsTo(Pregunta::class, 'id_pregunta', 'id');
    }

    /**
     * Respuestas registradas asociadas a esta opción específica
     */
    public function respuestas(): HasMany
    {
        return $this->hasMany(CuestionarioRespuesta::class, "id_pregunta_opcion", "id");
    }
}
