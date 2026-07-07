<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'id_perfil',
        'id_persona',
        "user_crea",
        "user_actualiza",
        "user_elimina",
        "estado"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * Obtener la persona asociada a un usuario
     * @return BelongsTo<Persona, $this>
     */
    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }

    /**
     * Obtener el perfil asociado a un usuario
     * @return BelongsTo<DetalleParametro, $this>
     */
    public function perfil(): BelongsTo
    {
        return $this->belongsTo(DetalleParametro::class, 'id_perfil', 'codigo');
    }

    /**
     * Verificar si un usuario tiene un perfil específico
     * @param string $nombrePerfil
     * @return bool
     */
    public function hasPerfil(string $nombrePerfil): bool
    {
        return $this->perfil?->nombre_url === $nombrePerfil;
    }
}
