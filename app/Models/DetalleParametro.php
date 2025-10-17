<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleParametro extends Model
{
    protected $table = "detalle_parametro";
    protected $primaryKey = "codigo";
    public $incrementing = true;

    protected $fillable = [
        'parametro_clase',
        'nombre',
        'nombre_url',
        'descripcion',
        'longitud',
        'en_persona',
        'en_empresa',
        'compra',
        'venta',
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
