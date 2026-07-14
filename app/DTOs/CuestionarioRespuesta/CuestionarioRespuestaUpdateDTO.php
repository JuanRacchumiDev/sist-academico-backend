<?php

namespace App\DTOs\CuestionarioRespuesta;

use Spatie\LaravelData\Data;

class CuestionarioRespuestaUpdateDTO extends Data
{
    public function __construct(
        public ?int $id_cuestionario_persona = null,
        public ?int $id_pregunta = null,
        public ?int $id_pregunta_opcion = null,
        public ?string $respuesta_texto = null,
        public ?float $puntaje_obtenido = null,
        public ?bool $es_correcta = null,
        public ?bool $estado = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
    ) {}

    public static function rules(): array
    {
        return [
            'id_cuestionario_persona' => [
                'sometimes',
                'integer',
                'exists:cuestionario_persona,id',
                'nullable'
            ],
            'id_pregunta' => [
                'sometimes',
                'integer',
                'exists:pregunta,id',
                'nullable'
            ],
            'id_pregunta_opcion' => [
                'sometimes',
                'integer',
                'exists:pregunta_opcion,id',
                'nullable',
            ],
            'respuesta_texto' => [
                'sometimes',
                'string',
                'nullable',
            ],
            'puntaje_obtenido' => [
                'sometimes',
                'numeric',
                'min:0',
                'nullable'
            ],
            'es_correcta' => [
                'sometimes',
                'boolean',
                'nullable',
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
