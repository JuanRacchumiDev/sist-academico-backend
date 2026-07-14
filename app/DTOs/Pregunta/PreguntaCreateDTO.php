<?php

namespace App\DTOs\Pregunta;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class PreguntaCreateDTO extends Data
{
    public function __construct(
        public string $enunciado,
        public string $tipo_respuesta,
        public bool $estado,
        public float $puntos = 1.00,
        public int $orden = 1,
        public ?int $id_cuestionario = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
    ) {}

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
                'required',
                'string'
            ],
            'tipo_respuesta' => [
                'required',
                'string',
                Rule::in(['RADIO', 'CHECKBOX', 'TEXTO'])
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
                'required',
                'boolean'
            ]
        ];
    }
}
