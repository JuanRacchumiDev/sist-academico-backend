<?php
namespace App\DTOs\Pago;

use Spatie\LaravelData\Data;

class PagoUpdateDTO extends Data
{
    public function __construct(
        public ?int $id_matricula = null,
        public ?int $id_modulo = null,
        public ?int $id_estadopago = null,
        public ?int $id_formapago = null,
        public ?string $concepto = null,
        public ?int $id_institucion,
        public ?bool $estado = true,
        public ?string $fecha_pago = null,
        public ?string $fecha_vencimiento = null,
        public ?float $cantidad = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null
    ){}

    public static function rules(): array
    {
        return [
            'id_matricula' => [
                'sometimes',
                'integer',
                'exists:matricula:id',
                'nullable'
            ],
            'id_modulo' => [
                'sometimes',
                'integer',
                'exists:modulo:id',
                'nullable'
            ],
            'id_estadopago' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro,codigo',
                'nullable'
            ],
            'id_formapago' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro,codigo',
                'nullable'
            ],
            'concepto' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'id_institucion' => [
                'sometimes',
                'integer',
                'exists:institucion,id',
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
            'cantidad' => [
                'sometimes',
                'float',
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