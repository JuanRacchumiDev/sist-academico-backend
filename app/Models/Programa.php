<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    protected $table = 'programa';

    protected $guarded = ['id'];

    protected $fillable = [
        'id_segmento',
        'codigo_old',
        'sigla',
        'nombre',
        'duracion',
        'modulos',
        'creditos',
        'plan',
        'user_crea',
        'user_actualiza',
        'user_elimina',
        'is_vigente',
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
        'is_visible' => 'boolean',
        'estado' => 'boolean'
    ];
}
