<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parametro extends Model
{
    protected $table = "parametro";
    protected $primaryKey = "clase";
    public $incrementing = false;

    protected $fillable = [
        "clase",
        "nombre",
        "nombre_url",
        "descripcion",
        "user_crea",
        "user_actualiza",
        "user_elimina",
        "estado"
    ];
}
