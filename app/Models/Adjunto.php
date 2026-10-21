<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Adjunto extends Model
{
    protected $table = 'academic.adjunto';

    protected $fillable = [
        'id_programa',
        'id_modulo',
        'id_sucursal',

        'tipo',
        'titulo',
        'titulo_url',
        'descripcion',
        'url',
        'filename',
        'originalname',
        'filepath',
        'mimetype',
        'size',
        'is_descargable',
        'is_visible',

        'disk',
        'bucket',
        'region',

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
        'estado' => 'boolean',
        'is_descargable' => 'boolean',
        'is_visible' => 'boolean'
    ];

    protected $appends = ['nombre_archivo', 'ruta_enlace'];

    public function programa(): BelongsTo
    {
        return $this->belongsTo(Programa::class, 'id_programa', 'id');
    }

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(Modulo::class, 'id_modulo', 'id');
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
     * Accessor dinámico para generar el enlace según el disco (Local, S3, Azure, etc.)
     */
    public function getRutaEnlaceAttribute(): ?string
    {
        if ($this->tipo !== 'FILE' || empty($this->filepath)) {
            return $this->url;
        }

        // Si el archivo ya es una URL externa
        if (filter_var($this->filepath, FILTER_VALIDATE_URL)) {
            return $this->filepath;
        }

        // Selecciona el disco configurado (por defecto 'public' o 'local')
        $diskName = $this->disk ?? config('filesystems.default', 'local');

        try {
            $disk = Storage::disk($diskName);
            return $disk->url($this->filepath);
        } catch(\Exception $e) {
            return null;
        }

        // Si almacenas URLs completas (S3, etc.), retorna directamente el filepath
        // if (filter_var($this->filepath, FILTER_VALIDATE_URL)) {
        //     return $this->filepath;
        // }

        // // Si usas el storage local de Laravel, generas la URL pública correspondiente
        // return asset('storage/' . $this->filepath);
    }
}
