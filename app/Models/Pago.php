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
        // 'id_modulo',
        'id_estadopago',
        'id_formapago',
        'id_institucion',
        'concepto',
        'numero_modulo',
        'numero_operacion',
        'fecha_pago',
        'fecha_vencimiento',
        'cantidad_efectivo',
        'cantidad_operacion',
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
        'estado' => 'boolean',
        'cantidad' => 'float'
    ];

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class, 'id_matricula', 'id');
    }

    // public function modulo(): BelongsTo
    // {
    //     return $this->belongsTo(Modulo::class, 'id_modulo', 'id');
    // }

    public function estadoPago(): BelongsTo
    {
        return $this->belongsTo(DetalleParametro::class, 'id_estadopago', 'codigo');
    }

    public function formaPago(): BelongsTo
    {
        return $this->belongsTo(DetalleParametro::class, 'id_formapago', 'codigo');
    }

    public function institucion(): BelongsTo
    {
        return $this->belongsTo(Institucion::class, 'id_institucion', 'id');
    }
}
