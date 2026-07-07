<?php

namespace App\DTOs\Matricula;

use Spatie\LaravelData\Data;

class MatriculaCreateDTO extends Data
{
    public function __construct(
        public int $id_persona,
        public int $id_estadomatricula,
        public int $id_institucion,
        public int $numero_modulos,
        public string $fecha_matricula,

        public int $id_formapago,
        public string $concepto_pago,
        public array $programas,
        public bool $estado,

        public ?int $id_modulo_pago = null,
        public ?int $id_estadopago = null,
        public ?string $numero_operacion = null,
        public ?float $cantidad_efectivo = null,
        public ?float $cantidad_operacion = null,

        public ?float $monto_matricula = null,
        public ?float $monto_modulo = null,

        public ?string $fecha_retiro = null,
        public ?string $fecha_reserva = null,
        public ?string $fecha_anula = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
    ) {}

    public static function rules(): array
    {
        return [
            'id_persona' => [
                'required',
                'integer',
                'exists:persona,id'
            ],
            'id_estadomatricula' => [
                'required',
                'integer',
                'exists:detalle_parametro,codigo'
            ],
            'id_institucion' => [
                'required',
                'integer',
                'exists:institucion,id'
            ],
            'numero_modulos' => [
                'required',
                'integer'
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
            'fecha_matricula' => [
                'required',
                'string'
            ],
            'monto_matricula' => [
                'sometimes',
                'float',
                'nullable'
            ],
            'monto_modulo' => [
                'sometimes',
                'float',
                'nullable'
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
            ],
            'estado' => [
                'required',
                'boolean'
            ]
        ];
    }
}
