<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pregunta extends Model
{
    protected $table = "pregunta";

    protected $guarded = ["id"];

    protected $fillable = [
        "id_cuestionario",
        "enunciado",
        "tipo_respuesta",
        "puntos",
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
        'estado' => 'boolean',
    ];

    public function cuestionario(): BelongsTo
    {
        return $this->belongsTo(Cuestionario::class, 'id_cuestionario', 'id');
    }

    public function opciones(): HasMany
    {
        return $this->hasMany(PreguntaOpcion::class, 'id_pregunta', 'id');
    }

    /**
     * Respuestas recibidas para esta pregunta
     */
    public function respuestas(): HasMany
    {
        return $this->hasMany(CuestionarioRespuesta::class, "id_pregunta", "id");
    }
}
