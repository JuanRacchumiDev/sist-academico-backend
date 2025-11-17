<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Persona extends Model
{
    // AÑADIDO: Declarar explícitamente la llave primaria
    protected $primaryKey = 'id';
    public $incrementing = true; // Por defecto es true
    protected $keyType = 'int'; // Por defecto es int

    protected $table = 'persona';

    protected $fillable = [
        'id_tipodocumento',
        'numero_documento',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'nombre_completo',
        'departamento',
        'provincia',
        'distrito',
        'direccion',
        'direccion_completa',
        'email',
        'telefono',
        'ubigeo_reniec',
        'ubigeo_sunat',
        'ubigeo',
        'fecha_nacimiento',
        'estado_civil',
        'foto',
        'sexo',
        'origen',
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
     * Obtener el tipo de documento asociado a una persona
     * @return BelongsTo<DetalleParametro, $this>
     */
    public function tipoDocumento(): BelongsTo
    {
        return $this->belongsTo(DetalleParametro::class, 'id_tipodocumento', 'codigo');
    }

    public function grupos(): BelongsToMany
    {
        return $this->belongsToMany(
            DetalleParametro::class,
            'grupo_persona',
            'id_persona', // Clave foránea del modelo actual (Persona) en la tabla pivote
            'codigo_detalle_parametro' // Clave foránea del modelo DetalleParametro en la tabla pivote
        )->withTimestamps();
    }
}