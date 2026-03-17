<?php
namespace App\DTOs\DocentePrograma;

use Spatie\LaravelData\Data;

class DocenteProgramaCreateDTO extends Data
{
    public function __construct(
        public int $id_persona,
        public int $id_programa,
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
            'id_programa' => [
                'required',
                'integer',
                'exists:programa,id'
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