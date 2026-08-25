<?php

namespace App\DTOs\DetalleParametro;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class DetalleParametroUpdateDTO extends Data
{
    public function __construct(
        public ?int $parametro_clase = null,
        public ?string $nombre = null,
        public ?string $nombre_url = null,
        public ?bool $estado = null,
        public ?string $descripcion = null,
        public ?string $valor = null,
        public ?string $abreviatura = null,
        public ?int $longitud = null,
        public ?bool $en_persona = null,
        public ?bool $en_empresa = null,
        public ?bool $compra = null,
        public ?bool $venta = null,
        public ?bool $visible = null,
        public ?string $fecha_crea = null,
        public ?string $fecha_actualiza = null,
        public ?string $fecha_elimina = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
    ) {}

    /**
     * Define los valores por defecto que se aplicarían si el campo no está presente
     * en el array de entrada
     */
    public static function withDefaults(): array
    {
        return [
            'en_persona' => false,
            'en_empresa' => false,
            'compra' => false,
            'venta' => false,
            'estado' => true,
            'visible' => true
        ];
    }

    public static function rules(): array
    {
        return [
            'parametro_clase' => [
                'sometimes',
                'integer',
                'exists:parametro,clase',
                'nullable'
            ],
            'nombre' => [
                'sometimes',
                'string',
                'max:100',
                Rule::unique('parametro', 'nombre'),
                'nullable'
            ],
            'nombre_url' => [
                'sometimes',
                'string',
                'max:120',
                Rule::unique('detalle_parametro', 'nombre_url'),
                'nullable'
            ],
            'descripcion' => [
                'sometimes',
                'string',
                'max: 100',
                'nullable'
            ],
            'valor' => [
                'sometimes',
                'string',
                'max: 20',
                'nullable'
            ],
            'abreviatura' => [
                'sometimes',
                'string',
                'max:10',
                'nullable'
            ],
            'longitud' => [
                'sometimes',
                'integer',
                'nullable'
            ],
            'en_persona' => [
                'sometimes',
                'boolean',
                'nullable'
            ],
            'en_empresa' => [
                'sometimes',
                'boolean',
                'nullable'
            ],
            'compra' => [
                'sometimes',
                'boolean',
                'nullable'
            ],
            'venta' => [
                'sometimes',
                'boolean',
                'nullable'
            ],
            'visible' => [
                'sometimes',
                'boolean',
                'nullable'
            ],
            'fecha_crea' => [
                'sometimes',
                'string:10',
                'nullable'
            ],
            'fecha_actualiza' => [
                'sometimes',
                'string:10',
                'nullable'
            ],
            'fecha_elimina' => [
                'sometimes',
                'string:10',
                'nullable'
            ],
            'user_crea' => [
                'sometimes',
                'string:12',
                'nullable'
            ],
            'user_actualiza' => [
                'sometimes',
                'string:12',
                'nullable'
            ],
            'user_elimina' => [
                'sometimes',
                'string:12',
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
