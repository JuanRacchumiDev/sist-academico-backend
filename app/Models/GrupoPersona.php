<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrupoPersona extends Model
{
    protected $table = "grupo_persona";

    protected $fillable = [
        'codigo_grupo',
        'id_persona',
        'id_sucursal',
        'fecha_crea',
        'fecha_actualiza',
        'fecha_elimina',
        'user_crea',
        'user_actualiza',
        'user_elimina'
    ];

    protected $hidden = [
        'user_crea',
        'user_actualiza',
        'user_elimina',
        'created_at',
        'updated_at'
    ];

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(DetalleParametro::class, 'codigo_grupo', 'codigo');
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }

    public function institucion(): BelongsTo
    {
        return $this->belongsTo(Institucion::class, 'id_sucursal', 'id');
    }
}
