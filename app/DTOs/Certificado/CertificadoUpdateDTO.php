<?php

namespace App\DTOs\Certificado;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class CertificadoUpdateDTO extends Data
{
    public function __construct(
        public ?int $id_persona = null,
        public ?int $codigo_tipocertificado = null,
        public ?int $id_sucursal = null,
        public ?bool $estado = null,
        public ?int $id_plantilla = null,
        public ?int $id_programa = null,
        public ?int $id_modulo = null,
        public ?string $codigo_verificacion = null,
        public ?string $path_file = null,
        public ?string $filename = null,
        public ?string $nombre_impresion = null,
        public ?string $codigo_qr_path = null,
        public ?string $fecha_crea = null,
        public ?string $fecha_actualiza = null,
        public ?string $fecha_elimina = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null
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

    public static function rules(): array
    {
        return [
            'id_persona' => [
                'sometimes',
                'integer',
                'exists:persona,id',
                'nullable'
            ],
            'codigo_tipocertificado' => [
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
            'id_plantilla' => [
                'sometimes',
                'integer',
                'exists:plantilla,id',
                'nullable'
            ],
            'id_programa' => [
                'sometimes',
                'integer',
                'exists:programa,id',
                'nullable'
            ],
            'id_modulo' => [
                'sometimes',
                'integer',
                'exists:modulo,id',
                'nullable'
            ],
            'codigo_verificacion' => [
                'sometimes',
                'string',
                'max:12',
                Rule::unique('certificado', 'codigo_verificacion'),
                'nullable'
            ],
            'codigo_qr_path' => [
                'sometimes',
                'string',
                'max:350',
                Rule::unique('certificado', 'codigo_qr_path'),
                'nullable'
            ],
            'path_file' => [
                'sometimes',
                'string',
                'max:350',
                'nullable'
            ],
            'filename' => [
                'sometimes',
                'string',
                'max:150',
                'nullable'
            ],
            'nombre_impresion' => [
                'sometimes',
                'string',
                'max:150',
                'nullable'
            ],
            'fecha_crea' => [
                'sometimes',
                'string:10',
                'nullable'
            ],
            'fecha_actualiza' => [
                'sometimes',
                'string:10',
                'nullable'
            ],
            'fecha_elimina' => [
                'sometimes',
                'string:10',
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
