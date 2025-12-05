<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificado extends Model
{
    protected $table = 'certificado';

    protected $fillable = [
        'id_persona',
        'id_tipocertificado',
        'id_plantilla',
        'id_programa',
        'codigo_qr_verificacion',
        'codigo_qr_path',
        'path_file',
        'filename',
        'nombre_impresion',
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
        return $this->belongsTo(DetalleParametro::class, 'id_tipocertificado', 'codigo');
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
}
