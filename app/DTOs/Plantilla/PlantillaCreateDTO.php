<?php
namespace App\DTOs\Plantilla;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class PlantillaCreateDTO extends Data
{
    public function __construct(
        public string $nombre,
        public string $path,
        public ?string $descripcion = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
        public ?bool $estado = true
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

    public static function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('plantilla', 'nombre')
            ],
            'path' => [
                'required',
                'string',
                'max:100'
            ],
            'descripcion' => [
                'sometimes',
                'string',
                'max:150',
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