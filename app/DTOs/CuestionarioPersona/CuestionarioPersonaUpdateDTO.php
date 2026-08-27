<?php

namespace App\DTOs\CuestionarioPersona;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class CuestionarioPersonaUpdateDTO extends Data
{
    public function __construct(
        public ?int $id_cuestionario = null,
        public ?int $id_persona = null,
        public ?int $numero_intento = null,
        public ?string $fecha_inicio = null,
        public ?string $fecha_fin = null,
        public ?float $puntaje_total = null,
        public ?string $estado_intento = null,
        public ?bool $estado = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
    ) {}

    public static function withDefaults(): array
    {
        return [
            'numero_intento' => 1,
            'estado_intento' => 'EN_PROCESO',
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
            'id_persona' => [
                'sometimes',
                'integer',
                'exists:persona,id',
                'nullable'
            ],
            'numero_intento' => [
                'sometimes',
                'integer',
                'min:1',
                'nullable'
            ],
            'fecha_inicio' => [
                'sometimes',
                'date_format:Y-m-d H:i:s',
                'nullable'
            ],
            'fecha_fin' => [
                'sometimes',
                'date_format:Y-m-d H:i:s',
                'after_or_equal:fecha_inicio',
                'nullable',
            ],
            'puntaje_total' => [
                'sometimes',
                'numeric',
                'min:0',
                'nullable',
            ],
            'estado_intento' => [
                'sometimes',
                'string',
                Rule::in(['EN_PROCESO', 'FINALIZADO', 'CORREGIDO']),
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
