<?php

namespace App\DTOs\User;

use App\DTOs\Persona\PersonaUpdateNestedDTO;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class UserUpdateDTO extends Data
{
    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
        public ?string $email = null,
        public ?string $password = null,
        public ?int $codigo_perfil = null,
        public ?int $id_persona = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
        public ?bool $estado = null,
        public ?PersonaUpdateNestedDTO $persona = null
    ) {}

    public static function rules(): array
    {
        $usuarioId = request()->route('usuario');

        return [
            'name' => [
                'sometimes',
                'string',
                'max:10',
                'nullable'
            ],
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:60',
                Rule::unique('users', 'email')->ignore($usuarioId),
                'nullable'
            ],
            'password' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'codigo_perfil' => [
                'sometimes',
                'int',
                'exists:detalle_parametro,codigo',
                'nullable'
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
                'sometimes',
                'boolean',
                'nullable'
            ]
        ];
    }
}
