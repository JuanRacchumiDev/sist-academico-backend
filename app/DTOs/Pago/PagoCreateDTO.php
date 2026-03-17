<?php
namespace App\DTOs\Pago;

use Spatie\LaravelData\Data;

class PagoCreateDTO extends Data
{
    public function __construct(
        public int $id_matricula,
        public int $id_modulo,
        public int $id_estadopago,
        public bool $estado = true,
        public ?int $id_institucion = null,
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
                'required',
                'integer',
                'exists:matricula:id'
            ],
            'id_modulo' => [
                'required',
                'integer',
                'exists:modulo:id'
            ],
            'id_estadopago' => [
                'required',
                'integer',
                'exists:detalle_parametro,codigo'
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
                'required',
                'boolean'
            ],
        ];
    }
}