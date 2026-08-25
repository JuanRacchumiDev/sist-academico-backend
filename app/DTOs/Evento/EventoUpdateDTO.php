<?php

namespace App\DTOs\Evento;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;
use Illuminate\Support\Str;

class EventoUpdateDTO extends Data
{
    public function __construct(
        public ?int $codigo_tipoevento = null,
        public ?int $codigo_categoriaevento = null,
        public ?string $titulo = null,
        public ?string $titulo_url = null,
        public ?string $fecha_inicio = null,
        public ?string $fecha_final = null,
        public ?string $duracion = null,
        public ?string $modalidad = null,
        public ?bool $estado = null,
        public ?string $descripcion = null,
        public ?string $temario = null,
        public ?float $precio = null,
        public ?int $capacidad_minima = null,
        public ?int $capacidad_maxima = null,
        public ?int $cantidad_inscritos = null,
        public ?string $fecha_crea = null,
        public ?string $fecha_actualiza = null,
        public ?string $fecha_elimina = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
    ) {}

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
        return [
            'codigo_tipoevento' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro,codigo',
                'nullable'
            ],
            'codigo_categoriaevento' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro,codigo',
                'nullable'
            ],
            'titulo' => [
                'sometimes',
                'string',
                'max:100',
                Rule::unique('evento', 'titulo'),
                'nullable'
            ],
            'titulo_url' => [
                'sometimes',
                'string',
                'max:120',
                Rule::unique('evento', 'titulo_url'),
                'nullable'
            ],
            'descripcion' => [
                'sometimes',
                'string',
                'max:120',
                'nullable',
            ],
            'temario' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'fecha_inicio' => [
                'sometimes',
                'string',
                'max:10',
                'nullable'
            ],
            'fecha_final' => [
                'sometimes',
                'string',
                'max:10',
                'nullable'
            ],
            'duracion' => [
                'sometimes',
                'string',
                'max:20',
                'nullable'
            ],
            'modalidad' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'precio' => [
                'sometimes',
                'float',
                'nullable'
            ],
            'capacidad_minima' => [
                'sometimes',
                'integer',
                'nullable'
            ],
            'capacidad_maxima' => [
                'sometimes',
                'integer',
                'nullable'
            ],
            'cantidad_inscritos' => [
                'sometimes',
                'integer',
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
                'sometimes',
                'boolean',
                'nullable'
            ]
        ];
    }
}
