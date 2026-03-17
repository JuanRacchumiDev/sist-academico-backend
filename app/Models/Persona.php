<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Persona extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

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
            'id_persona',
            'codigo_detalle_parametro'
        )->withTimestamps();
    }

    public function matriculas(): HasMany {
        return $this->hasMany(Matricula::class, 'id_persona', 'id');
    }
}