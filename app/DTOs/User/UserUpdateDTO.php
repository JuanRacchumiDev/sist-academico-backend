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
        public ?int $id_perfil = null,
        public ?int $id_persona = null,
        public ?bool $estado = true,
        public ?PersonaUpdateNestedDTO $persona = null
    ){}

    public static function rules(): array
    {
        $usuarioId = request()->route('usuario');

        return [
            'name' => [
                'sometimes',
                'string',
                'max:10',
            ],
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:60',
                Rule::unique('users', 'email')->ignore($usuarioId)
            ],
            'password' => [
                'sometimes',
                'string'
            ],
            'id_perfil' => [
                'sometimes',
                'int',
                'exists:detalle_parametro,codigo'
            ],
            'id_persona' => [
                'sometimes',
                'int',
                'exists:persona,id'
            ]
        ];
    }
}