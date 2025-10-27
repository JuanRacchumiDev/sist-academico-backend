<?php
namespace App\DTOs\Plantilla;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class PlantillaUpdateDTO extends Data
{
    public function __construct(
        public ?string $nombre = null,
        public ?string $descripcion = null,
        public ?string $path = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
        public ?bool $estado = null
    ){}

    public static function rules(): array
    {
        $plantillaId = request()->route('plantilla');

        return [
            'nombre' => [
                'sometimes',
                'string',
                'max:100',
                Rule::unique('plantilla', 'nombre')->ignore($plantillaId)
            ],
            'descripcion' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'path' => [
                'sometimes',
                'string'
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