<?php
namespace App\DTOs\DetalleParametro;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class DetalleParametroCreateDTO extends Data
{
    public function __construct(
        public int $parametro_clase,
        public string $nombre,
        public bool $estado = true,
        public ?string $nombre_url,
        public ?string $descripcion = null,
        public ?string $valor = null,
        public ?int $longitud = null,
        public ?bool $en_persona = false,
        public ?bool $en_empresa = false,
        public ?bool $compra = false,
        public ?bool $venta = false,
        public ?bool $visible = false,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
    ){}

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
                'required',
                'integer',
                'exists:parametro,clase'
            ],
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('parametro', 'nombre')
            ],
            'nombre_url' => [
                'required',
                'string',
                'max:120',
                Rule::unique('detalle_parametro', 'nombre_url')
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
            'longitud' => [
                'sometimes',
                'integer',
                'nullable'
            ],
            'en_persona' => [
                'sometimes',
                'boolean'
            ],
            'en_empresa' => [
                'sometimes',
                'boolean'
            ],
            'compra' => [
                'sometimes',
                'boolean'
            ],
            'venta' => [
                'sometimes',
                'boolean'
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