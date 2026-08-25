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
        'codigo_tipodocumento',
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

    /**
     * Obtener el tipo de documento asociado a una persona
     * @return BelongsTo<DetalleParametro, $this>
     */
    public function tipoDocumento(): BelongsTo
    {
        return $this->belongsTo(DetalleParametro::class, 'codigo_tipodocumento', 'codigo');
    }

    public function grupos(): BelongsToMany
    {
        return $this->belongsToMany(
            DetalleParametro::class,
            'grupo_persona',
            'id_persona',
            'codigo_grupo'
        )->withTimestamps();
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class, 'id_persona', 'id');
    }

    public function certificados(): HasMany
    {
        return $this->hasMany(Certificado::class, 'id_persona', 'id');
    }

    public function cuestionarios(): BelongsToMany
    {
        return $this->belongsToMany(
            Cuestionario::class,
            "cuestionario_persona",
            "id_persona",
            "id_cuestionario"
        )
            ->using(CuestionarioPersona::class)
            ->withPivot([
                'id',
                'numero_intento',
                'fecha_inicio',
                'fecha_fin',
                'puntaje_total',
                'estado_intento',
                'estado'
            ])
            ->withTimestamps();
    }

    /**
     * Obtener el historial de todos los intentos de cuestionarios de la persona.
     */
    public function intentosCuestionarios(): HasMany
    {
        return $this->hasMany(CuestionarioPersona::class, 'id_persona', 'id');
    }
}
