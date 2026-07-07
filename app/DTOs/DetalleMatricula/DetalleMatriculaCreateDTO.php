<?php

namespace App\DTOs\DetalleMatricula;

use Spatie\LaravelData\Data;

class DetalleMatriculaCreateDTO extends Data
{
    public function __construct(
        public int $id_matricula,
        public int $id_programa,
        public ?float $valor_matricula = null,
        public ?float $valor_modulo = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
    ) {}

    public static function rules(): array
    {
        return [
            'id_matricula' => [
                'required',
                'integer',
                'exists:matricula,id'
            ],
            'id_programa' => [
                'required',
                'integer',
                'exists:programa,id'
            ],
            'valor_matricula' => [
                'sometimes',
                'float',
                'nullable',
            ],
            'valor_modulo' => [
                'sometimes',
                'float',
                'nullable',
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
