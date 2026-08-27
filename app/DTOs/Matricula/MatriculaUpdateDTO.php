<?php

namespace App\DTOs\Matricula;

use Spatie\LaravelData\Data;

class MatriculaUpdateDTO extends Data
{
    public function __construct(
        public ?int $id_persona = null,
        public ?int $codigo_estadomatricula = null,
        public ?int $id_sucursal = null,
        public ?int $numero_modulos = null,
        public ?array $programas = null,
        public ?string $fecha_matricula = null,
        public ?bool $estado = null,

        // --- Datos específicos del Pago de Matrícula (OBLIGATORIO) ---
        public ?float $monto_matricula = null,
        public ?int $codigo_formapago_matricula = null,
        public ?string $concepto_matricula = null,
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
                'sometimes',
                'integer',
                'exists:persona,id',
                'nullable'
            ],
            'codigo_estadomatricula' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro,codigo',
                'nullable'
            ],
            'id_sucursal' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro,codigo',
                'nullable'
            ],
            'numero_modulos' => [
                'sometimes',
                'integer',
                'min:1',
                'nullable'
            ],
            'programas' => [
                'sometimes',
                'array',
                'min:1',
                'nullable'
            ],
            'programas.*' => [
                'sometimes',
                'integer',
                'exists:programa,id',
                'nullable'
            ],
            'fecha_matricula' => [
                'sometimes',
                'string',
                'nullable'
            ],

            // Validaciones para Pago de Matrícula
            'monto_matricula' => [
                'sometimes',
                'numeric',
                'gt:0',
                'nullable'
            ],
            'codigo_formapago_matricula' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro,codigo',
                'nullable'
            ],
            'concepto_matricula' => [
                'sometimes',
                'string',
                'nullable'
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
