<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Plantilla extends Model
{
    protected $table = 'plantilla';

    protected $guarded = ['id'];

    protected $fillable = [
        'id_institucion',
        'codigo_tipoprograma',
        'nombre',
        'descripcion',
        'path_imagen_fondo',
        'path_imagen_publica',
        'path_pdf_fondo',
        'tipo_disenio',
        'disenio_default',
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

    public function institucion(): BelongsTo
    {
        return $this->belongsTo(Institucion::class, 'id_institucion', 'id');
    }

    public function tipoPrograma(): BelongsTo
    {
        return $this->belongsTo(DetalleParametro::class, 'codigo_tipoprograma', 'codigo');
    }
}
