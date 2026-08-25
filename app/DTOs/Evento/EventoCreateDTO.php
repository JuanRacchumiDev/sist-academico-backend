<?php

namespace App\DTOs\Evento;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;
use Illuminate\Support\Str;

class EventoCreateDTO extends Data
{
    public function __construct(
        public int $codigo_tipoevento,
        public int $codigo_categoriaevento,
        public string $titulo,
        public string $titulo_url,
        public string $fecha_inicio,
        public string $fecha_final,
        public string $duracion,
        public string $modalidad,
        public bool $estado,
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
                'required',
                'integer',
                'exists:detalle_parametro,codigo'
            ],
            'codigo_categoriaevento' => [
                'required',
                'integer',
                'exists:detalle_parametro,codigo'
            ],
            'titulo' => [
                'required',
                'string',
                'max:100',
                Rule::unique('evento', 'titulo')
            ],
            'titulo_url' => [
                'required',
                'string',
                'max:120',
                Rule::unique('evento', 'titulo_url')
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
                'required',
                'string',
                'max:10'
            ],
            'fecha_final' => [
                'required',
                'string',
                'max:10'
            ],
            'duracion' => [
                'required',
                'string',
                'max:20'
            ],
            'modalidad' => [
                'required',
                'string'
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
                'required',
                'boolean'
            ]
        ];
    }
}
