<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Adjunto extends Model
{
    protected $table = 'adjunto';

    protected $fillable = [
        'id_programa',
        'id_modulo',
        'id_institucion',

        'titulo',
        'titulo_url',
        'descripcion',
        'filename',
        'originalname',
        'filepath',
        'mimetype',
        'size',
        'es_descargable',
        'es_visible',
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

    public function programa(): BelongsTo
    {
        return $this->belongsTo(Programa::class, 'id_programa', 'id');
    }

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(modulo::class, 'id_modulo', 'id');
    }

    public function institucion(): BelongsTo
    {
        return $this->belongsTo(Institucion::class, 'id_institucion', 'id');
    }
}
