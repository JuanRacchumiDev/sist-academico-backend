<?php

namespace App\DTOs\Parametro;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class ParametroCreateDTO extends Data
{
    public ?int $clase;

    public function __construct(
        public string $nombre,
        public string $nombre_url,
        public bool $estado,
        public ?string $descripcion = null,
        public ?string $fecha_crea = null,
        public ?string $fecha_actualiza = null,
        public ?string $fecha_elimina = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
    ) {}

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
            'fecha_crea' => [
                'sometimes',
                'string',
                'max:10',
                'nullable'
            ],
            'fecha_actualiza' => [
                'sometimes',
                'string',
                'max:10',
                'nullable'
            ],
            'fecha_elimina' => [
                'sometimes',
                'string',
                'max:10',
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
            ]
        ];
    }
}
