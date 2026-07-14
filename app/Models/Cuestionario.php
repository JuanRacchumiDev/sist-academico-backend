<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cuestionario extends Model
{
    protected $table = "cuestionario";

    protected $guarded = ["id"];

    protected $fillable = [
        "id_programa",
        "id_modulo",
        "titulo",
        "descripcion",
        "duracion_minutos",
        "nota_minima_aprobatoria",
        "intentos_permitidos",
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

    public function programa(): BelongsTo
    {
        return $this->belongsTo(Programa::class, 'id_programa', 'id');
    }

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(Modulo::class, 'id_modulo', 'id');
    }

    public function preguntas(): HasMany
    {
        return $this->hasMany(Pregunta::class, 'id_cuestionario', 'id');
    }

    public function personas(): BelongsToMany
    {
        return $this->belongsToMany(
            Persona::class,
            "cuestionario_persona",
            "id_cuestionario",
            "id_persona"
        )
            ->using(CuestionarioPersona::class)
            ->withPivot([
                "id",
                "numero_intento",
                "fecha_inicio",
                "fecha_fin",
                "puntaje_total",
                "estado_intento",
                "estado"
            ])
            ->withTimestamps();
    }

    /**
     * Relación directa con todos los intentos registrados.
     */
    public function intentos(): HasMany
    {
        return $this->hasMany(CuestionarioPersona::class, "id_cuestionario", "id");
    }
}
