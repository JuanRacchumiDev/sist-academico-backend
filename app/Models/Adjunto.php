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
        'id_sucursal',

        'titulo',
        'titulo_url',
        'descripcion',
        'filename',
        'originalname',
        'filepath',
        'mimetype',
        'size',
        'is_descargable',
        'is_visible',
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

    protected $appends = ['nombre_archivo', 'ruta_enlace'];

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
        return $this->belongsTo(Institucion::class, 'id_sucursal', 'id');
    }

    /**
     * Accessor para mapear el originalname al estándar del frontend
     */
    public function getNombreArchivoAttribute(): string
    {
        return $this->originalname ?? $this->titulo ?? 'Archivo Adjunto';
    }

    /**
     * Accessor para estructurar la URL absoluta de descarga
     */
    public function getRutaEnlaceAttribute(): string
    {
        // Si almacenas URLs completas (S3, etc.), retorna directamente el filepath
        if (filter_var($this->filepath, FILTER_VALIDATE_URL)) {
            return $this->filepath;
        }

        // Si usas el storage local de Laravel, generas la URL pública correspondiente
        return asset('storage/' . $this->filepath);
    }
}
