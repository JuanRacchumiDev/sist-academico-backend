<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaginaLegal extends Model
{
    protected $table = "web.pagina_legal";

    protected $fillable = [
        'titulo',
        'titulo_url',
        'contenido_html',
        'version',
        'meta_title',
        'meta_description',
        'is_publicado',

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
