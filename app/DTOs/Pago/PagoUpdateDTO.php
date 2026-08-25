<?php

namespace App\DTOs\Pago;

use Spatie\LaravelData\Data;

class PagoUpdateDTO extends Data
{
    public function __construct(
        public ?int $id_matricula = null,
        public ?int $codigo_formapago = null,
        public ?int $id_sucursal = null,

        public ?string $concepto = null,
        public ?bool $estado = null,

        public ?int $id_modulo = null,
        public ?int $codigo_estadopago = null,

        public ?int $numero_modulo = null,
        public ?string $numero_operacion = null,
        public ?string $fecha_pago = null,
        public ?string $fecha_vencimiento = null,
        public ?float $cantidad_efectivo = null,
        public ?float $cantidad_operacion = null,

        public ?string $fecha_crea = null,
        public ?string $fecha_actualiza = null,
        public ?string $fecha_elimina = null,

        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null
    ) {}

    public static function rules(): array
    {
        return [
            'id_matricula' => [
                'sometimes',
                'integer',
                'exists:matricula,id',
                'nullable'
            ],
            'codigo_formapago' => [
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
            'id_modulo' => [
                'sometimes',
                'integer',
                'exists:modulo,id',
                'nullable',
            ],
            'codigo_estadopago' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro,codigo',
                'nullable',
            ],
            'concepto' => [
                'sometimes',
                'string',
                'max:200',
                'nullable'
            ],
            'numero_modulo' => [
                'sometimes',
                'integer',
                'nullable'
            ],
            'numero_operacion' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'fecha_pago' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'fecha_vencimiento' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'cantidad_efectivo' => [
                'sometimes',
                'numeric',
                'min:0',
                'nullable'
            ],
            'cantidad_operacion' => [
                'sometimes',
                'numeric',
                'min:0',
                'nullable'
            ],
            'fecha_crea' => [
                'sometimes',
                'string',
                'max:10',
                'nullable'
            ],
            'fecha_actualiza' => [
                'sometimes',
                'string',
                'max:10',
                'nullable'
            ],
            'fecha_elimina' => [
                'sometimes',
                'string',
                'max:10',
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
        ];
    }
}
