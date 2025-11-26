<?php
namespace App\DTOs\DetalleParametro;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;
use Illuminate\Support\Str;

class DetalleParametroUpdateDTO extends Data
{
    public function __construct(
        public ?int $parametro_clase = null,
        public ?string $nombre = null,
        public ?string $nombre_url = null,
        public ?string $descripcion = null,
        public ?string $valor = null,
        public ?string $abreviatura = null,
        public ?int $longitud = null,
        public ?bool $en_persona = null,
        public ?bool $en_empresa = null,
        public ?bool $compra = null,
        public ?bool $venta = null,
        public ?bool $visible = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
        public ?bool $estado = null
    ){}

    /**
     * Manipula los datos de la validación
     */
    // public static function prepareForValidation(array $payload): array
    // {
    //     // Verificamos si el campo nombre está en la petición
    //     if (isset($payload['nombre'])) {
    //         $payload['nombre_url'] = Str::slug($payload['nombre']);
    //     }

    //     return $payload;
    // }

    public static function rules(): array
    {
        $detalleParametroId = request()->route('detalleParametro');

        return [
            'parametro_clase' => [
                'required',
                'integer',
                'exists:parametro,clase'
            ],
            'nombre' => [
                'sometimes',
                'string',
                'max:100',
                // Rule::unique('detalle_parametro', 'nombre')
                //     ->ignore($detalleParametroId)
            ],
            'nombre_url' => [
                'sometimes',
                'string',
                'max:120',
                // Rule::unique('detalle_parametro', 'nombre_url')
                //     ->ignore($detalleParametroId)
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
                'max: 10',
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
            'visible' => [
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
                'sometimes',
                'boolean'
            ]
        ];
    }
}