<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompromisoPago extends Model
{
    protected $table = "compromiso_pago";
    protected $primaryKey = 'id';
    public $incrementing = true;

    public $timestamps = true;

    protected $fillable = [
        'id_alumno',
        'id_pago',
        'id_modulo',
        'fecha_proximo_pago',
        'fecha_vencimiento',
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

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_alumno', 'id');
    }

    public function pago(): BelongsTo
    {
        return $this->belongsTo(Pago::class, 'id_pago', 'id');
    }    

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(Modulo::class, 'id_modulo', 'id');
    }
}
