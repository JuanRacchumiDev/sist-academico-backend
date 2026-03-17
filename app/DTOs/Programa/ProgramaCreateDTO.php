<?php
namespace App\DTOs\Programa;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;
use Illuminate\Http\UploadedFile;

class ProgramaCreateDTO extends Data {
    public function __construct(
        public string $titulo,
        public string $titulo_url,
        public string $modalidad,
        public bool $is_vigente,
        public bool $estado,
        public ?int $id_segmento = null,
        public ?int $id_tipoprograma = null,
        public ?int $id_categoriaprograma = null,
        public ?int $id_institucion = null,
        public ?string $codigo_old = null,
        public ?string $sigla = null,
        public ?string $descripcion = null,
        public ?string $temario = null,
        public ?string $fecha_inicio = null,
        public ?string $fecha_final = null,
        public ?string $duracion = null,
        public ?int $horas_academicas = null,
        public ?int $numero_modulos = null,
        public ?int $creditos = null,
        public ?string $plan = null,
        public ?int $capacidad_minima = null,
        public ?int $capacidad_maxima = null,
        public ?int $cantidad_inscritos = null,
        public ?float $precio_modulo = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null
    ){}

    public static function rules(): array
    {
        return [
            'id_segmento' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro:codigo',
                'nullable'
            ],
            'id_tipoprograma' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro:codigo',
                'nullable'
            ],
            'id_categoriaprograma' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro:codigo',
                'nullable'
            ],
            'id_institucion' => [
                'sometimes',
                'integer',
                'exists:institucion:id',
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
                'required',
                'string',
                'max:100'
            ],
            'titulo_url' => [
                'required',
                'string',
                'max:120'
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
                'required',
                'string',
                'max:20'
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
                'required',
                'boolean'
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
            ],
        ];
    }
}