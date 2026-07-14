<?php

namespace App\DTOs\Persona;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class PersonaUpdateNestedDTO extends Data
{
    public function __construct(
        public int $id,
        public ?int $id_tipodocumento = null,
        public ?string $numero_documento = null,
        public ?string $nombres = null,
        public ?string $apellido_paterno = null,
        public ?string $apellido_materno = null,
        public ?string $nombre_completo = null,
        public ?string $departamento = null,
        public ?string $provincia = null,
        public ?string $distrito = null,
        public ?string $direccion = null,
        public ?string $direccion_completa = null,
        public ?string $email = null,
        public ?string $telefono = null,
        public ?string $ubigeo_reniec = null,
        public ?string $ubigeo_sunat = null,
        public ?string $ubigeo = null,
        public ?string $fecha_nacimiento = null,
        public ?string $estado_civil = null,
        public ?string $foto = null,
        public ?string $sexo = null,
        public ?string $origen = "WEB",
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
        public ?bool $estado = null
    ) {}

    public static function rules(): array
    {
        $personaId = request()->route('persona');

        return [
            'id_tipodocumento' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro,codigo',
                'nullable'
            ],
            'numero_documento' => [
                'sometimes',
                'string',
                'max:13',
                Rule::unique('persona', 'numero_documento')->ignore($personaId),
                'nullable'
            ],
            'nombres' => [
                'sometimes',
                'string',
                'max:30',
                'nullable'
            ],
            'apellido_paterno' => [
                'sometimes',
                'string',
                'max:30',
                'nullable'
            ],
            'apellido_materno' => [
                'sometimes',
                'string',
                'max:30',
                'nullable'
            ],
            'departamento' => [
                'sometimes',
                'string',
                'max:50',
                'nullable'
            ],
            'provincia' => [
                'sometimes',
                'string',
                'max:50',
                'nullable'
            ],
            'distrito' => [
                'sometimes',
                'string',
                'max:50',
                'nullable'
            ],
            'direccion' => [
                'sometimes',
                'string',
                'max:50',
                'nullable'
            ],
            'direccion_completa' => [
                'sometimes',
                'string',
                'max:150',
                'nullable'
            ],
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:60',
                Rule::unique('persona', 'email')->ignore($personaId),
                'nullable'
            ],
            'telefono' => [
                'sometimes',
                'string',
                'max:13',
                'nullable'
            ],
            'ubigeo_reniec' => [
                'sometimes',
                'string',
                'max:12',
                'nullable'
            ],
            'ubigeo_sunat' => [
                'sometimes',
                'string',
                'max:12',
                'nullable'
            ],
            'ubigeo' => [
                'sometimes',
                'string',
                'max:12',
                'nullable'
            ],
            'fecha_nacimiento' => [
                'sometimes',
                'string',
                'max:10',
                'nullable'
            ],
            'estado_civil' => [
                'sometimes',
                'string',
                'max:20',
                'exists:detalle_parametro,codigo',
                'nullable'
            ],
            'foto' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'sexo' => [
                'sometimes',
                'string',
                'exists:detalle_parametro,codigo',
                'nullable'
            ],
            'user_crea' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'user_actualiza' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'user_elimina' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'estado' => [
                'sometimes',
                'boolean',
                'nullable'
            ]
        ];
    }
}
