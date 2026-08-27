<?php

namespace App\DTOs\Modulo;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class ModuloUpdateDTO extends Data
{
    public function __construct(
        public ?string $titulo = null,
        public ?string $titulo_url = null,
        public ?int $orden = null,
        public ?bool $estado = null,
        public ?int $id_programa = null,
        public ?string $descripcion = null,
        public ?string $temario = null,
        public ?float $nota = null,
        public ?string $fecha_crea = null,
        public ?string $fecha_actualiza = null,
        public ?string $fecha_elimina = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null
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
            'id_programa' => [
                'sometimes',
                'integer',
                'exists:programa,id',
                'nullable'
            ],
            'titulo' => [
                'sometimes',
                'string',
                'max:100',
                Rule::unique('modulo', 'titulo'),
                'nullable'
            ],
            'titulo_url' => [
                'sometimes',
                'string',
                'max:120',
                Rule::unique('modulo', 'titulo_url'),
                'nullable'
            ],
            'descripcion' => [
                'sometimes',
                'string',
                'max:150',
                'nullable'
            ],
            'temario' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'nota' => [
                'sometimes',
                'numeric',
                'min:0',
                'nullable'
            ],
            'orden' => [
                'sometimes',
                'integer',
                'nullable'
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
                'max:10',
                'nullable'
            ],
            'user_actualiza' => [
                'sometimes',
                'string',
                'max:10',
                'nullable'
            ],
            'user_elimina' => [
                'sometimes',
                'string',
                'max:10',
                'nullable'
            ],
            'estado' => [
                'sometimes',
                'boolean',
                'nullable'
            ],
        ];
    }
}
