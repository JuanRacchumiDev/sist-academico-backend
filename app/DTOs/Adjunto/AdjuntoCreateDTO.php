<?php

namespace App\DTOs\Adjunto;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class AdjuntoCreateDTO extends Data
{
    public function __construct(
        public string $titulo,
        public ?string $titulo_url = null,
        public ?string $filename = null,
        public ?string $originalname = null,
        public ?string $filepath = null,
        public ?string $mimetype = null,
        public ?int $size = null,
        public bool $es_descargable = true,
        public bool $es_visible = true,
        public bool $estado = true,
        public ?int $id_programa = null,
        public ?int $id_modulo = null,
        public ?int $id_institucion = null,
        public ?string $descripcion = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
    ) {}

    public static function withDefaults(): array
    {
        return [
            'es_descargable' => true,
            'es_visible' => true,
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
            'id_institucion' => [
                'sometimes',
                'integer',
                'exists:institucion,id',
                'nullable'
            ],
            'titulo' => [
                'required',
                'string',
                'max:100'
            ],
            'titulo_url' => [
                'sometimes',
                'string',
                'max:120'
            ],
            'descripcion' => [
                'sometimes',
                'string',
                'max:150',
                'nullable'
            ],
            'file' => [
                'required',
                'file',
                'max:10240'
            ],
            'filename' => [
                'sometimes',
                'string',
                'max:120'
            ],
            'originalname' => [
                'sometimes',
                'string',
                'max:180'
            ],
            'filepath' => [
                'sometimes',
                'string',
                'max:150'
            ],
            'mimetype' => [
                'sometimes',
                'string',
                'max:40'
            ],
            'size' => [
                'sometimes',
                'integer'
            ],
            'es_descargable' => [
                'sometimes',
                'boolean'
            ],
            'es_visible' => [
                'sometimes',
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
                'boolean'
            ]
        ];
    }
}
