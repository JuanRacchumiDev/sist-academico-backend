<?php
namespace App\DTOs\Parametro;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;
use Illuminate\Support\Str;

class ParametroCreateDTO extends Data
{
    public function __construct(
        public string $nombre,
        public bool $estado = true,
        public ?int $clase = null,
        public ?string $nombre_url = null,
        public ?string $descripcion = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
    ){}

    /**
     * Define los valores por defecto para los campos opcionales/booleanos
     */
    public static function withDefaults(): array
    {
        return [
            'estado' => true
        ];
    }

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
        return [
            'clase' => [
                'required',
                'integer',
                Rule::unique('parametro', 'clase')
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
                Rule::unique('parametro', 'nombre_url')
            ],
            'descripcion' => [
                'sometimes',
                'string',
                'max: 100',
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