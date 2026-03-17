<?php
namespace App\DTOs\DocenteModulo;

use Spatie\LaravelData\Data;

class DocenteModuloCreateDTO extends Data
{
    public function __construct(
        public int $id_persona,
        public int $id_modulo,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
    ){}

    public static function rules(): array
    {
        return [
            'id_persona' => [
                'required',
                'integer',
                'exists:persona,id'
            ],
            'id_modulo' => [
                'required',
                'integer',
                'exists:modulo,id'
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
        ];
    } 
}