<?php

namespace App\DTOs\User;

use Spatie\LaravelData\Data;

class UserCreateDTO extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public int $codigo_perfil,
        public bool $estado,
        public ?int $id_persona = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
    ) {}

    public static function withDefaults(): array
    {
        return [
            'estado' => true
        ];
    }

    public static function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:10',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:60',
            ],
            'password' => [
                'required',
                'string'
            ],
            'codigo_perfil' => [
                'required',
                'int',
                'exists:detalle_parametro,codigo'
            ],
            'id_persona' => [
                'sometimes',
                'int',
                'exists:persona,id',
                'nullable'
            ],
            'user_crea' => [
                'sometimes',
                'string',
                'max:12',
                'nullable'
            ],
            'user_actualiza' => [
                'sometimes',
                'string',
                'max:12',
                'nullable'
            ],
            'user_elimina' => [
                'sometimes',
                'string',
                'max:12',
                'nullable'
            ],
            'estado' => [
                'required',
                'boolean'
            ]
        ];
    }
}
