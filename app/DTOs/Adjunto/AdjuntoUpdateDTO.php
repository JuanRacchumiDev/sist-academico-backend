<?php

namespace App\DTOs\Adjunto;

use Spatie\LaravelData\Data;

class AdjuntoUpdateDTO extends Data
{
    public function __construct(
        public ?string $titulo = null,
        public ?bool $is_descargable = null,
        public ?bool $is_visible = null,
        public ?bool $estado = null,

        public ?int $id_programa = null,
        public ?int $id_modulo = null,
        public ?int $id_sucursal = null,
        public ?string $titulo_url = null,
        public ?string $descripcion = null,
        public ?string $filename = null,
        public ?string $originalname = null,
        public ?string $filepath = null,
        public ?string $mimetype = null,
        public ?int $size = null,

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
            'is_descargable' => true,
            'is_visible' => true,
            'estado' => true
        ];
    }

    public static function rules(): array
    {
        return [
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
            'id_sucursal' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro,codigo',
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
            'file' => [
                'sometimes',
                'file',
                'max:10240',
                'nullable'
            ],
            'filename' => [
                'sometimes',
                'string',
                'max:120',
                'nullable'
            ],
            'originalname' => [
                'sometimes',
                'string',
                'max:180',
                'nullable'
            ],
            'filepath' => [
                'sometimes',
                'string',
                'max:150',
                'nullable'
            ],
            'mimetype' => [
                'sometimes',
                'string',
                'max:40',
                'nullable'
            ],
            'size' => [
                'sometimes',
                'integer',
                'nullable'
            ],
            'is_descargable' => [
                'sometimes',
                'boolean',
                'nullable'
            ],
            'is_visible' => [
                'sometimes',
                'boolean',
                'nullable'
            ],
            'fecha_crea' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'fecha_actualiza' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'fecha_elimina' => [
                'sometimes',
                'string',
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
