<?php

namespace App\DTOs\Cuestionario;

use App\DTOs\Pregunta\PreguntaCreateDTO;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

class CuestionarioUpdateDTO extends Data
{
    public function __construct(
        public ?string $titulo = null,
        public ?int $intentos_permitidos = null,
        public ?bool $estado = null,
        public ?int $id_programa = null,
        public ?int $id_modulo = null,
        public ?string $descripcion = null,
        public ?int $duracion_minutos = null,
        public ?float $nota_minima_aprobatoria = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,

        /** @var PreguntaCreateDTO[]|null */
        #[DataCollectionOf(PreguntaCreateDTO::class)]
        public ?array $preguntas = null,
    ) {}

    public static function rules(): array
    {
        return [
            'id_programa' => [
                'sometimes',
                'integer',
                'exists:programa,id',
                'nullable'
            ],
            'id_modulo' => [
                'sometimes',
                'integer',
                'exists:modulo,id',
                'nullable'
            ],
            'titulo' => [
                'sometimes',
                'string',
                'max:150',
                'nullable'
            ],
            'descripcion' => [
                'sometimes',
                'text',
                'nullable'
            ],
            'duracion_minutos' => [
                'sometimes',
                'integer',
                'nullable'
            ],
            'nota_minima_aprobatoria' => [
                'sometimes',
                'numeric',
                'min:0',
                'nullable'
            ],
            'intentos_permitidos' => [
                'sometimes',
                'numeric',
                'min:0',
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
            'preguntas' => [
                'sometimes',
                'array',
                'nullable'
            ],
            'preguntas.*.id' => [
                'sometimes',
                'integer',
                'exists:pregunta,id',
                'nullable'
            ],
            'preguntas.*.enunciado' => [
                'required_with:preguntas',
                'string'
            ],
            'preguntas.*.tipo_respuesta' => [
                'required_with:preguntas',
                'string',
                'in:RADIO,CHECKBOX,TEXTO'
            ],
            'preguntas.*.puntos' => [
                'sometimes',
                'numeric',
                'min:0'
            ],
            'preguntas.*.orden' => [
                'sometimes',
                'integer',
                'min:1'
            ],
            'preguntas.*.estado' => [
                'required_with:preguntas',
                'boolean'
            ],
        ];
    }
}
