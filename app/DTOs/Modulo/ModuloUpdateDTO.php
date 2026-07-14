<?php

namespace App\DTOs\Modulo;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class ModuloUpdateDTO extends Data
{
    public function __construct(
        public ?int $id = null,
        public ?int $id_programa = null,
        public ?int $id_institucion = null,
        public ?string $titulo = null,
        public ?string $titulo_url = null,
        public ?int $orden = null,
        public ?bool $estado = true,
        public ?string $descripcion = null,
        public ?string $temario = null,
        public ?float $nota = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null
    ) {}

    public static function rules(): array
    {
        return [
            'id' => [
                'sometimes',
                'integer',
                'exists:modulo,id',
                'nullable'
            ],
            'id_programa' => [
                'sometimes',
                'integer',
                'exists:programa,id',
                'nullable'
            ],
            'id_institucion' => [
                'sometimes',
                'integer',
                'exists:institucion,id',
                'nullable'
            ],
            'titulo' => [
                'sometimes',
                'string',
                'max:100',
                // Rule::unique('modulo', 'titulo'),
                'nullable'
            ],
            'titulo_url' => [
                'sometimes',
                'string',
                'max:120',
                // Rule::unique('modulo', 'titulo_url'),
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
            ],
        ];
    }
}
