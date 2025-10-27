<?php
namespace App\DTOs\User;

use Spatie\LaravelData\Data;

class UserCreateDTO extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public int $id_perfil,
        public bool $estado = true,
        public ?int $id_persona = null
    ){}

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
            'id_perfil' => [
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
            'estado' => [
                'required',
                'boolean'
            ]
        ];
    }
}