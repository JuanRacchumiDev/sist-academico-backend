<?php
namespace App\DTOs\GrupoPersona;

use Spatie\LaravelData\Data;

class GrupoPersonaUpdateDTO extends Data
{
    public function __construct(
        public ?string $codigo_detalle_parametro,
        public ?int $id_persona,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
    ){}

    public static function rules(): array
    {
        return [
            'codigo_detalle_parametro' => [
                'sometimes',
                'string',
                'exists:detalle_parametro,codigo',
                'nullable',
            ],
            'id_persona' => [
                'sometimes',
                'integer',
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
        ];
    } 
}