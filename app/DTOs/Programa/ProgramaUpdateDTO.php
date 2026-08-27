<?php

namespace App\DTOs\Programa;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;
use Illuminate\Http\UploadedFile;

class ProgramaUpdateDTO extends Data
{
    public function __construct(
        public ?int $codigo_segmento = null,
        public ?int $codigo_tipoprograma = null,
        public ?int $codigo_categoriaprograma = null,
        public ?int $id_sucursal = null,
        public ?string $codigo_old = null,
        public ?string $sigla = null,
        public ?string $titulo = null,
        public ?string $titulo_url = null,
        public ?string $descripcion = null,
        public ?string $temario = null,
        public ?string $fecha_inicio = null,
        public ?string $fecha_final = null,
        public ?string $duracion = null,
        public ?int $horas_academicas = null,
        public ?int $numero_modulos = null,
        public ?int $creditos = null,
        public ?string $plan = null,
        public ?string $modalidad = null,
        public ?int $capacidad_minima = null,
        public ?int $capacidad_maxima = null,
        public ?int $cantidad_inscritos = null,
        public ?int $precio_modulo = null,
        public ?bool $is_vigente = null,
        public ?string $fecha_crea = null,
        public ?string $fecha_actualiza = null,
        public ?string $fecha_elimina = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
        public ?bool $estado = null
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
            'codigo_segmento' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro,codigo',
                'nullable'
            ],
            'codigo_tipoprograma' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro,codigo',
                'nullable'
            ],
            'codigo_categoriaprograma' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro,codigo',
                'nullable'
            ],
            'id_sucursal' => [
                'sometimes',
                'integer',
                'exists:institucion,id',
                'nullable'
            ],
            'codigo_old' => [
                'sometimes',
                'string',
                'max:10',
                Rule::unique('programa', 'codigo_old'),
                'nullable'
            ],
            'sigla' => [
                'sometimes',
                'string',
                'max:10',
                Rule::unique('programa', 'sigla'),
                'nullable'
            ],
            'titulo' => [
                'sometimes',
                'string',
                'max:100',
                'nullable'
            ],
            'titulo_url' => [
                'sometimes',
                'string',
                'max:120',
                'nullable'
            ],
            'descripcion' => [
                'sometimes',
                'string',
                'max:150',
                'nullable'
            ],
            'temario' => [
                'sometimes',
                'text',
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
            'horas_academicas' => [
                'sometimes',
                'integer',
                'nullable'
            ],
            'numero_modulos' => [
                'sometimes',
                'integer',
                'nullable'
            ],
            'creditos' => [
                'sometimes',
                'integer',
                'nullable'
            ],
            'plan' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'modalidad' => [
                'sometimes',
                'string',
                'max:20',
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
            'precio_modulo' => [
                'sometimes',
                'float',
                'nullable',
            ],
            'is_vigente' => [
                'sometimes',
                'boolean',
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
                'max:10',
                'nullable'
            ],
            'user_actualiza' => [
                'sometimes',
                'string',
                'max:10',
                'nullable'
            ],
            'user_elimina' => [
                'sometimes',
                'string',
                'max:10',
                'nullable'
            ],
            'estado' => [
                'sometimes',
                'boolean',
                'nullable'
            ],
        ];
    }
}
