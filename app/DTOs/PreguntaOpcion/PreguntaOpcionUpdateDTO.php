<?php

namespace App\DTOs\PreguntaOpcion;

use Spatie\LaravelData\Data;

class PreguntaOpcionUpdateDTO extends Data
{
    public function __construct(
        public ?int $id_pregunta = null,
        public ?string $texto_opcion = null,
        public ?bool $es_correcta = null,
        public ?int $orden = null,
        public ?bool $estado = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
    ) {}

    public static function withDefaults(): array
    {
        return [
            'es_correcta' => false,
            'orden' => 1,
            'estado' => true
        ];
    }

    public static function rules(): array
    {
        return [
            'id_pregunta' => [
                'sometimes',
                'integer',
                'exists:pregunta,id',
                'nullable'
            ],
            'texto_opcion' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'es_correcta' => [
                'sometimes',
                'boolean',
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
                'nullable',
            ],
            'user_actualiza' => [
                'sometimes',
                'string',
                'max:12',
                'nullable',
            ],
            'user_elimina' => [
                'sometimes',
                'string',
                'max:12',
                'nullable',
            ],
            'estado' => [
                'sometimes',
                'boolean',
                'nullable'
            ],
        ];
    }
}
