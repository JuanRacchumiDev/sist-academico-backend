<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plantilla extends Model
{
    protected $table = 'plantilla';

    protected $fillable = [
        'nombre',
        'descripcion',
        'path',
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
}
