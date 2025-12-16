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
        "id_alumno",
        "id_sede",
        "id_metodopago",
        "id_estadomatricula",
        'nombre_alumno',
        'nombre_sede',
        'nombre_formapago',
        'nombre_estadomatricula',
        "fecha_matricula",
        "pago_inicial",
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

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_alumno', 'id');
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(DetalleParametro::class, 'id_sede', 'codigo');
    }

    public function metodoPago(): BelongsTo
    {
        return $this->belongsTo(DetalleParametro::class, 'id_metodopago', 'codigo');
    }

    public function estadoMatricula(): BelongsTo
    {
        return $this->belongsTo(DetalleParametro::class, 'id_estadomatricula', 'codigo');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleMatricula::class, 'id_matricula', 'id');
    }
}