<?php

namespace App\DTOs\Evento;

use Spatie\LaravelData\Data;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EventoUpdateDTO extends Data
{
    public function __construct(
        public ?int $id_tipoevento = null,
        public ?int $id_categoriaevento = null,
        public ?string $titulo = null,
        public ?string $titulo_url = null,
        public ?string $descripcion = null,
        public ?string $temario = null,
        public ?string $fecha_inicio = null,
        public ?string $fecha_final = null,
        public ?string $duracion = null,
        public ?string $modalidad = null,
        public ?float $precio = null,
        public ?int $capacidad_minima = null,
        public ?int $capacidad_maxima = null,
        public ?int $cantidad_inscritos = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
        public ?bool $estado = null
    ) {}

    /**
     * Manipula los datos de la validación
     */
    public static function prepareForValidation(array $payload): array
    {
        // Verificamos si el campo nombre está en la petición
        if (isset($payload['titulo'])) {
            $payload['titulo_url'] = Str::slug($payload['titulo']);
        }

        return $payload;
    }

    public static function rules(): array
    {
        $eventoId = request()->route('evento');

        return [
            'id_tipoevento' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro,codigo',
                'nullable'
            ],
            'id_categoriaevento' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro,codigo',
                'nullable'
            ],
            'titulo' => [
                'sometimes',
                'string',
                'max:100',
                Rule::unique('evento', 'titulo')->ignore($eventoId),
                'nullable'
            ],
            'titulo_url' => [
                'sometimes',
                'string',
                'max:120',
                Rule::unique('evento', 'titulo_url')->ignore($eventoId),
                'nullable'
            ],
            'descripcion' => [
                'sometimes',
                'string',
                'max:100',
                'nullable'
            ],
            'temario' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'fecha_inicio' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'fecha_final' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'duracion' => [
                'sometimes',
                'string',
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
                'boolean',
                'nullable'
            ]
        ];
    }
}
