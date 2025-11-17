<?php
namespace App\DTOs\Parametro;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;
use Illuminate\Support\Str;

class ParametroCreateDTO extends Data
{
    public ?int $clase; 

    public function __construct(
        public string $nombre,
        public string $nombre_url,
        public bool $estado = true,
        public ?string $descripcion = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
    ){}

    /**
     * Define los valores por defecto para los campos opcionales/booleanos
     */
    // public static function withDefaults(): array
    // {
    //     return [
    //         'estado' => true
    //     ];
    // }

    public static function rules(): array
    {
        return [
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
                'sometimes',
                'boolean'
            ]
        ];
    }
}