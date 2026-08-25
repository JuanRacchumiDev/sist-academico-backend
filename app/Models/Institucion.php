<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Institucion extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $table = 'institucion';

    protected $fillable = [
        'codigo_sede',
        'nombre',
        'sigla',
        'ruc',
        'direccion',
        'telefono_contacto',
        'logo_path',
        'firma_digital',
        'color_primario',
        'nombre_director',
        'nombre_representante',
        'firma_director_path',
        'firma_representante_path',
        'is_cliente',
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

    public function sede(): BelongsTo
    {
        return $this->belongsTo(DetalleParametro::class, 'codigo_sede', 'codigo');
    }
}
