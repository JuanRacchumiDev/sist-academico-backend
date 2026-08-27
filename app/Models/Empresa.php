<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $primaryKey = "id";
    public $incrementing = true;
    protected $keyType = "int";

    protected $table = "empresa";

    protected $fillable = [
        'numero_ruc',
        'razon_social',
        'tipo_contribuyente',
        'estado_sunat',
        'condicion_sunat',
        'departamento',
        'provincia',
        'distrito',
        'direccion',
        'direccion_completa',
        'ubigeo_sunat',
        'origen',
        'telefonos',
        'horario_atencion',
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
