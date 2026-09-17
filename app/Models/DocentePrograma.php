<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocentePrograma extends Model
{
    protected $table = "academic.docente_programa";

    public $timestamps = true;

    protected $fillable = [
        "id_persona",
        "id_programa",

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

    public function programa(): BelongsTo
    {
        return $this->belongsTo(Programa::class, 'id_programa', 'id');
    }
}
