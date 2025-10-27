<?php
namespace App\DTOs\Evento;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;
use Illuminate\Support\Str;

class EventoCreateDTO extends Data
{
    public function __construct(
        public int $id_tipoevento,
        public int $id_categoriaevento,
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
            'id_tipoevento' => [
                'required',
                'integer',
                'exists:detalle_parametro,codigo'
            ],
            'id_categoriaevento' => [
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
                'max:100',
                'nullable',
            ],
            'temario' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'fecha_inicio' => [
                'required',
                'string'
            ],
            'fecha_final' => [
                'required',
                'string'
            ],
            'duracion' => [
                'required',
                'string'
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