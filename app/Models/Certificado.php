<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificado extends Model
{
    protected $table = 'certificado';

    protected $fillable = [
        'id_persona',
        'codigo_tipocertificado',
        'id_sucursal',
        'id_plantilla',
        'id_programa',
        'id_modulo',

        'codigo_verificacion',
        'codigo_qr_path',
        'path_file',
        'filename',
        'nombre_impresion',

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

    /**
     * Obtener la persona asociada a un certificado
     * @return BelongsTo<Persona, $this>
     */
    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }

    /**
     * Obtener el tipo de certificado a un certificado
     * @return BelongsTo<DetalleParametro, $this>
     */
    public function tipoCertificado(): BelongsTo
    {
        return $this->belongsTo(DetalleParametro::class, 'codigo_tipocertificado', 'codigo');
    }

    /**
     * Obtener la institución asociado a un certificado
     * @return BelongsTo<Institucion, $this>
     */
    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Institucion::class, 'id_sucursal', 'id');
    }

    /**
     * Obtener la plantilla a un certificado
     * @return BelongsTo<Plantilla, $this>
     */
    public function plantilla(): BelongsTo
    {
        return $this->belongsTo(Plantilla::class, 'id_plantilla', 'id');
    }

    /**
     * Obtener el programa asociado a un certificado
     * @return BelongsTo<Programa, $this>
     */
    public function programa(): BelongsTo
    {
        return $this->belongsTo(Programa::class, 'id_programa', 'id');
    }

    /**
     * Obtener el módulo asociado a un certificado
     * @return BelongsTo<Modulo, $this>
     */
    public function modulo(): BelongsTo
    {
        return $this->belongsTo(Modulo::class, 'id_modulo', 'id');
    }
}
