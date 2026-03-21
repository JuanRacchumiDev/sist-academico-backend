<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matricula extends Model
{
    protected $table = "matricula";

    protected $guarded = ["id"];

    protected $fillable = [
        "id_persona",
        "id_estadomatricula",
        "id_institucion",
        "fecha_matricula",
        "fecha_retiro",
        "fecha_reserva",
        "fecha_anula",
        "user_crea",
        "user_actualiza",
        "user_elimina",
        "estado"
    ];

    protected $hidden = [
        "user_crea",
        "user_actualiza",
        "user_elimina",
        "created_at",
        "updated_at"
    ];

    protected $casts = [
        'estado' => 'boolean'
    ];

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }

    public function estadoMatricula(): BelongsTo
    {
        return $this->belongsTo(DetalleParametro::class, 'id_estadomatricula', 'codigo');
    }

    public function institucion(): BelongsTo
    {
        return $this->belongsTo(Institucion::class, 'id_institucion', 'id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleMatricula::class, 'id_matricula', 'id');
    }
}