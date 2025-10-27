<?php
namespace App\DTOs\Parametro;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;
use Illuminate\Support\Str;

class ParametroUpdateDTO extends Data
{
    public function __construct(
        public int $clase,
        public ?string $nombre = null,
        public ?string $nombre_url = null,
        public ?string $descripcion = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
        public ?bool $estado = null
    ){}

    /**
     * Manipula los datos de la validación
     */
    public static function prepareForValidation(array $payload): array
    {
        // Verificamos si el campo nombre está en la petición
        if (isset($payload['nombre'])) {
            $payload['nombre_url'] = Str::slug($payload['nombre']);
        }

        return $payload;
    }

    public static function rules(): array
    {
        $parametroId = request()->route('parametro');

        return [
            'clase' => [
                'required',
                'integer',
                Rule::unique('parametro', 'clase')
            ],
            'nombre' => [
                'sometimes',
                'string',
                'max:100',
                Rule::unique('parametro', 'nombre')->ignore($parametroId)
            ],
            'nombre_url' => [
                'sometimes',
                'string',
                'max:120',
                Rule::unique('parametro', 'nombre_url')->ignore($parametroId)
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