<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocenteModulo extends Model
{
    protected $table = "academic.docente_modulo";

    public $timestamps = true;

    protected $fillable = [
        "id_persona",
        "id_modulo",

        "fecha_crea",
        "fecha_actualiza",
        "fecha_elimina",

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

    public function docente(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(Modulo::class, 'id_modulo', 'id');
    }
}
