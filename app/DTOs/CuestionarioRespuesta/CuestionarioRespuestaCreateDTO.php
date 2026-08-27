<?php

namespace App\DTOs\CuestionarioRespuesta;

use Spatie\LaravelData\Data;

class CuestionarioRespuestaCreateDTO extends Data
{
    public function __construct(
        public int $id_cuestionario_persona,
        public int $id_pregunta,
        public bool $estado,
        public float $puntaje_obtenido,
        public ?int $id_pregunta_opcion = null,
        public ?string $respuesta_texto = null,
        public ?bool $es_correcta = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
    ) {}

    public static function withDefaults(): array
    {
        return [
            'estado' => true,
            'puntaje_obtenido' => 0.00
        ];
    }

    public static function rules(): array
    {
        return [
            'id_cuestionario_persona' => [
                'required',
                'integer',
                'exists:cuestionario_persona,id',
            ],
            'id_pregunta' => [
                'required',
                'integer',
                'exists:pregunta,id',
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
                'required',
                'boolean',
            ],
        ];
    }
}
