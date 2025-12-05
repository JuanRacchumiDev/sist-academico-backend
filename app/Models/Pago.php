<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    protected $table = 'pago';

    protected $guarded = ['id'];

    protected $fillable = [
        'id_matricula',
        'id_alumno',
        'id_formapago',
        'id_metodopago',
        'id_estadopago',
        'concepto',
        'fecha_pago',
        'nro_operacion',
        'fecha_proximo_pago',
        'fecha_compromiso_pago',
        'nro_cuota',
        'monto_efectivo',
        'monto_tarjeta',
        'monto_total',
        'monto_pagado',
        'monto_saldo',
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
        'estado' => 'boolean'
    ];

    /**
     * Obtener la matrícula
     * @return BelongsTo<Matricula, $this>
     */
    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class, 'id_matricula', 'id');
    }

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_alumno', 'id');
    }

    public function formaPago(): BelongsTo
    {
        return $this->belongsTo(DetalleParametro::class, 'id_formapago', 'codigo');
    }

    public function metodoPago(): BelongsTo
    {
        return $this->belongsTo(DetalleParametro::class, 'id_metodopago', 'codigo');
    }

    public function estadoPago(): BelongsTo
    {
        return $this->belongsTo(DetalleParametro::class, 'id_estadopago', 'codigo');
    }
}
