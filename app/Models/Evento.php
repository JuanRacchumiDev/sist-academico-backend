<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evento extends Model
{
    protected $table = 'evento';

    protected $guarded = ['id'];

    protected $fillable = [
        'id_tipoevento',
        'id_categoriaevento',
        'titulo',
        'titulo_url',
        'descripcion',
        'temario',
        'fecha_inicio',
        'fecha_final',
        'duracion',
        'modalidad',
        'precio',
        'capacidad_minima',
        'capacidad_maxima',
        'cantidad_inscritos',
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
     * Obtener el tipo de evento a un evento
     * @return BelongsTo<DetalleParametro, $this>
     */
    public function tipoEvento(): BelongsTo
    {
        return $this->belongsTo(DetalleParametro::class, 'id_tipoevento', 'codigo');
    }

    /**
     * Obtener el tipo de categoría a un evento
     * @return BelongsTo<DetalleParametro, $this>
     */
    public function categoriaEvento(): BelongsTo
    {
        return $this->belongsTo(DetalleParametro::class, 'id_categoriaevento', 'codigo');
    }
}
