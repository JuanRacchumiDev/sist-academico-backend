<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = "web.configuracion";

    protected $fillable = [
        'clave',
        'clave_slug',
        'valor',
        'grupo',
        'etiqueta',
        'tipo_input',

        'fecha_crea',
        'fecha_actualiza',
        'fecha_elimina',

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
}
