<?php

namespace App\DTOs\CuestionarioPersona;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class CuestionarioPersonaCreateDTO extends Data
{
    public function __construct(
        public int $id_cuestionario,
        public int $id_persona,
        public string $fecha_inicio,
        public bool $estado = true,
        public int $numero_intento = 1,
        public string $estado_intento = 'EN_PROCESO',
        public ?string $fecha_fin = null,
        public ?float $puntaje_total = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
    ) {}

    public static function rules(): array
    {
        return [
            'id_cuestionario' => [
                'required',
                'integer',
                'exists:cuestionario,id',
            ],
            'id_persona' => [
                'required',
                'integer',
                'exists:persona,id',
            ],
            'numero_intento' => [
                'sometimes',
                'integer',
                'min:1',
            ],
            'fecha_inicio' => [
                'required',
                'date_format:Y-m-d H:i:s',
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
