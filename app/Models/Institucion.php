<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institucion extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $table = 'institucion';

    protected $fillable = [
        'nombre',
        'sigla',
        'ruc',
        'ubicacion',
        'telefono_contacto',
        'logo_path',
        'firma_digital',
        'color_primario',

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
