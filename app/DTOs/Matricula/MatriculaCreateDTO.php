<?php

namespace App\DTOs\Matricula;

use Spatie\LaravelData\Data;

class MatriculaCreateDTO extends Data
{
    public function __construct(
        public int $id_persona,
        public int $codigo_estadomatricula,
        public int $id_sucursal,
        public int $numero_modulos,
        public string $fecha_matricula,
        public array $programas,
        public bool $estado,

        // --- Datos específicos del Pago de Matrícula (OBLIGATORIO) ---
        public float $monto_matricula,
        public int $codigo_formapago_matricula,
        public string $concepto_matricula,
        public ?string $numero_operacion_matricula = null,
        public ?float $monto_efectivo_matricula = null,
        public ?float $monto_operacion_matricula = null,

        // --- Datos opcionales del Primer Pago de Módulo ---
        public ?bool $pagarPrimerModulo = false,
        public ?float $monto_modulo = null,
        public ?int $codigo_formapago_modulo = null,
        public ?string $concepto_modulo = null,
        public ?string $numero_operacion_modulo = null,
        public ?float $monto_efectivo_modulo = null,
        public ?float $monto_operacion_modulo = null,

        // --- Auditoría / Fechas suplementarias ---
        public ?string $fecha_retiro = null,
        public ?string $fecha_reserva = null,
        public ?string $fecha_anula = null,
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
            'id_persona' => [
                'required',
                'integer',
                'exists:persona,id'
            ],
            'codigo_estadomatricula' => [
                'required',
                'integer',
                'exists:detalle_parametro,codigo'
            ],
            'id_sucursal' => [
                'required',
                'integer',
                'exists:detalle_parametro,codigo'
            ],
            'numero_modulos' => [
                'required',
                'integer',
                'min:1'
            ],
            'fecha_matricula' => [
                'required',
                'string'
            ],
            'programas' => [
                'required',
                'array',
                'min:1'
            ],
            'programas.*' => [
                'required',
                'integer',
                'exists:programa,id'
            ],
            'estado' => [
                'required',
                'boolean'
            ],

            // Validaciones para Pago de Matrícula
            'monto_matricula' => [
                'required',
                'numeric',
                'gt:0'
            ],
            'codigo_formapago_matricula' => [
                'required',
                'integer',
                'exists:detalle_parametro,codigo'
            ],
            'concepto_matricula' => [
                'required',
                'string'
            ],
            'numero_operacion_matricula' => [
                'nullable',
                'string'
            ],
            'monto_efectivo_matricula' => [
                'nullable',
                'numeric',
                'min:0'
            ],
            'monto_operacion_matricula' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            // Validaciones para Pago de Módulo
            'pagarPrimerModulo' => [
                'nullable',
                'boolean'
            ],
            'monto_modulo' => [
                'nullable',
                'numeric',
                'min:0'
            ],
            'codigo_formapago_modulo' => [
                'required_if:pagarPrimerModulo,true',
                'nullable',
                'integer',
                'exists:detalle_parametro,codigo'
            ],
            'concepto_modulo' => [
                'nullable',
                'string'
            ],
            'numero_operacion_modulo' => [
                'nullable',
                'string'
            ],
            'monto_efectivo_modulo' => [
                'nullable',
                'numeric',
                'min:0'
            ],
            'monto_operacion_modulo' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            // Auditoría
            'fecha_retiro' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'fecha_reserva' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'fecha_anula' => [
                'sometimes',
                'string',
                'nullable'
            ],

            'user_crea' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'user_actualiza' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'user_elimina' => [
                'sometimes',
                'string',
                'nullable'
            ]
        ];
    }
}
