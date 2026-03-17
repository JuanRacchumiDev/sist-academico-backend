<?php
namespace App\DTOs\Parametro;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class ParametroUpdateDTO extends Data
{
    public ?int $clase;

    public function __construct(
        public ?string $nombre = null,
        public ?string $nombre_url = null,
        public ?string $descripcion = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
        public ?bool $estado = null
    ){}

    public static function rules(): array
    {
        $data = app(self::class);
        $clase = $data->clase ?? null;

        return [
            'nombre' => [
                'sometimes',
                'string',
                'max:100',
                Rule::unique('parametro', 'nombre')->ignore($clase, 'clase'),
                'nullable'
            ],
            'nombre_url' => [
                'sometimes',
                'string',
                'max:120',
                Rule::unique('parametro', 'nombre_url')->ignore($clase, 'clase'),
                'nullable'
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
            ]
        ];
    }
}