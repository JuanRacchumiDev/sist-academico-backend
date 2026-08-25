<?php

namespace App\DTOs\DetalleMatricula;

use Spatie\LaravelData\Data;

class DetalleMatriculaCreateDTO extends Data
{
    public function __construct(
        public int $id_matricula,
        public int $id_programa,
        public bool $estado,
        public ?float $valor_matricula = null,
        public ?float $valor_modulo = null,
        public ?string $fecha_crea = null,
        public ?string $fecha_actualiza = null,
        public ?string $fecha_elimina = null,
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
            'fecha_crea' => [
                'sometimes',
                'string',
                'max:10',
                'nullable'
            ],
            'fecha_actualiza' => [
                'sometimes',
                'string',
                'max:10',
                'nullable'
            ],
            'fecha_elimina' => [
                'sometimes',
                'string',
                'max:10',
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
