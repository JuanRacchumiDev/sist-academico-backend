<?php

namespace App\DTOs\Institucion;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class InstitucionCreateDTO extends Data
{
    public function __construct(
        public string $nombre,
        public bool $is_cliente,
        public bool $estado,
        public ?int $codigo_sede = null,
        public ?string $sigla = null,
        public ?string $ruc = null,
        public ?string $direccion = null,
        public ?string $telefono_contacto = null,
        public ?string $logo_path = null,
        public ?string $firma_digital = null,
        public ?string $color_primario = null,
        public ?string $nombre_director = null,
        public ?string $nombre_representante = null,
        public ?string $firma_director_path = null,
        public ?string $firma_representante_path = null,

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
            'codigo_sede' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro,codigo',
                'nullable'
            ],
            'nombre' => [
                'required',
                'string',
                'max:60'
            ],
            'sigla' => [
                'sometimes',
                'string',
                'max:100',
                'nullable'
            ],
            'ruc' => [
                'sometimes',
                'string',
                'max:13',
                'nullable'
            ],
            'direccion' => [
                'sometimes',
                'string',
                'max:100',
                'nullable'
            ],
            'telefono_contacto' => [
                'sometimes',
                'string',
                'max:20',
                'nullable'
            ],
            'logo_path' => [
                'sometimes',
                'string',
                'max:150',
                'nullable'
            ],
            'firma_digital' => [
                'sometimes',
                'string',
                'max:150',
                'nullable'
            ],
            'color_primario' => [
                'sometimes',
                'string',
                'max:20',
                'nullable'
            ],
            'nombre_director' => [
                'sometimes',
                'string',
                'max:150',
                'nullable'
            ],
            'nombre_representante' => [
                'sometimes',
                'string',
                'max:150',
                'nullable'
            ],
            'firma_director_path' => [
                'sometimes',
                'string',
                'max:150',
                'nullable'
            ],
            'firma_representante_path' => [
                'sometimes',
                'string',
                'max:150',
                'nullable'
            ],
            'is_cliente' => [
                'required',
                'boolean'
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
                'boolean',
            ],
        ];
    }
}
