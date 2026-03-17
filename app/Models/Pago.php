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
        'id_modulo',
        'id_estadopago',
        'fecha_pago',
        'fecha_vencimiento',
        'cantidad',
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

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(Modulo::class, 'id_modulo', 'id');
    }

    public function estadoPago(): BelongsTo
    {
        return $this->belongsTo(DetalleParametro::class, 'id_estadopago', 'codigo');
    }
}
