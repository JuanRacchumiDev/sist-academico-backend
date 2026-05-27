<?php

namespace App\DTOs\Pago;

use Spatie\LaravelData\Data;

class PagoCreateDTO extends Data
{
    public function __construct(
        public int $id_matricula,
        public int $id_formapago,
        public int $id_institucion,

        public string $concepto,
        public bool $estado,

        public ?int $id_modulo = null,
        public ?int $id_estadopago = null,

        public ?string $numero_operacion = null,
        public ?string $fecha_pago = null,
        public ?string $fecha_vencimiento = null,
        public ?float $cantidad_efectivo = null,
        public ?float $cantidad_operacion = null,

        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null
    ) {}

    public static function rules(): array
    {
        return [
            'id_matricula' => [
                'required',
                'integer',
                'exists:matricula:id'
            ],
            'id_modulo' => [
                'sometimes',
                'integer',
                'exists:modulo:id',
                'nullable',
            ],
            'id_estadopago' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro,codigo',
                'nullable',
            ],
            'id_formapago' => [
                'required',
                'integer',
                'exists:detalle_parametro,codigo'
            ],
            'id_institucion' => [
                'required',
                'integer',
                'exists:institucion,id'
            ],
            'concepto' => [
                'required',
                'string',
                'max:200'
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
                'required',
                'boolean'
            ],
        ];
    }
}
