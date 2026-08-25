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
        "codigo_estadomatricula",
        "id_sucursal",
        'numero_modulos',
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
        return $this->belongsTo(DetalleParametro::class, 'codigo_estadomatricula', 'codigo');
    }

    public function institucion(): BelongsTo
    {
        return $this->belongsTo(Institucion::class, 'id_sucursal', 'id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleMatricula::class, 'id_matricula', 'id');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'id_matricula', 'id');
    }

    public function pagoMatricula(): HasMany
    {
        return $this->pagos()->where('concepto', 'LIKE', '%PAGO%DE%MATRÍCULA%');
    }

    public function pagoModulos(): HasMany
    {
        return $this->pagos()->where('concepto', 'LIKE', '%PAGO%DE%MÓDULO%');
    }
}
