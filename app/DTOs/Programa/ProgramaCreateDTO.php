<?php
namespace App\DTOs\Programa;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class ProgramaCreateDTO extends Data {
    public function __construct(
        public string $nombre,
        public string $modalidad,
        public bool $is_vigente = true,
        public bool $estado = true,
        public int $valor_cuota,
        public ?int $id_segmento = null,
        public ?int $id_tipoprograma = null,
        public ?int $id_categoriaprograma = null,
        public ?string $codigo_old = null,
        public ?string $sigla = null,
        public ?string $nombre_url = null,
        public ?string $descripcion = null,
        public ?string $duracion = null,
        public ?string $fecha_inicio = null,
        public ?string $fecha_final = null,
        public ?int $modulos = null,
        public ?int $creditos = null,
        public UploadedFile|string|null $plan,
        public ?string $temario = null,
        public ?int $capacidad_minima = null,
        public ?int $capacidad_maxima = null,
        public ?int $cantidad_inscritos = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null
    ){}

    /**
     * Manipula los datos de la validación
     */
    // public static function prepareForValidation(array $payload): array
    // {
    //     // Verificamos si el campo nombre está en la petición
    //     if (isset($payload['nombre'])) {
    //         $payload['nombre_url'] = Str::slug($payload['nombre']);
    //     }

    //     return $payload;
    // }

    public static function rules(): array
    {
        return [
            'id_segmento' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro:codigo'
            ],
            'id_tipoprograma' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro:codigo'
            ],
            'id_categoriaprograma' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro:codigo'
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
            'nombre' => [
                'required',
                'string',
                'max:100'
            ],
            'nombre_url' => [
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
            'duracion' => [
                'sometimes',
                'string',
                'max:20',
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
            'modulos' => [
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
                'file',
                'mimes:pdf',
                'max:2048',
                'nullable'
            ],
            'modalidad' => [
                'required',
                'string',
                'max:50'
            ],
            'temario' => [
                'sometimes',
                'string',
                'max:1000',
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
            'valor_cuota' => [
                'required',
                'integer',
                'integer'
            ],
            'is_vigente' => [
                'required',
                'boolean'
            ],
            'estado' => [
                'required',
                'boolean'
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
            ]
        ];
    }
}