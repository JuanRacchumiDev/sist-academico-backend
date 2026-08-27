<?php

namespace App\DTOs\PreguntaOpcion;

use Spatie\LaravelData\Data;

class PreguntaOpcionCreateDTO extends Data
{
    public function __construct(
        public int $id_pregunta,
        public string $texto_opcion,
        public bool $es_correcta,
        public int $orden,
        public bool $estado = true,
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
                'required',
                'integer',
                'exists:pregunta,id',
            ],
            'texto_opcion' => [
                'required',
                'string',
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
                'required',
                'boolean',
            ],
        ];
    }
}
