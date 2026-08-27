<?php

namespace App\DTOs\Pregunta;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class PreguntaUpdateDTO extends Data
{
    public function __construct(
        public ?int $id_cuestionario = null,
        public ?string $enunciado = null,
        public ?string $tipo_respuesta = null,
        public ?bool $estado = null,
        public ?float $puntos = null,
        public ?int $orden = null,
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
            'id_cuestionario' => [
                'sometimes',
                'integer',
                'exists:cuestionario,id',
                'nullable'
            ],
            'enunciado' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'tipo_respuesta' => [
                'sometimes',
                'string',
                Rule::in(['RADIO', 'CHECKBOX', 'TEXTO']),
                'nullable'
            ],
            'puntos' => [
                'sometimes',
                'numeric',
                'min:0',
                'nullable'
            ],
            'orden' => [
                'sometimes',
                'integer',
                'min:1',
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
