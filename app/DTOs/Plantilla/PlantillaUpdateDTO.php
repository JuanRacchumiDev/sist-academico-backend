<?php

namespace App\DTOs\Plantilla;

use Spatie\LaravelData\Data;
use Illuminate\Http\UploadedFile;

class PlantillaUpdateDTO extends Data
{
    public function __construct(
        public ?int $id_institucion = null,
        public ?int $codigo_tipoprograma = null,
        public ?string $nombre = null,
        public UploadedFile|string|null $path_imagen_fondo = null,
        public UploadedFile|string|null $path_imagen_publica = null,
        public UploadedFile|string|null $path_pdf_fondo = null,
        public ?string $tipo_disenio = null,
        public ?string $disenio_default = null,
        public ?string $descripcion = null,
        public ?string $fecha_crea = null,
        public ?string $fecha_actualiza = null,
        public ?string $fecha_elimina = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
        public ?bool $estado = null,
    ) {}

    public static function withDefaults(): array
    {
        return [
            'estado' => true
        ];
    }

    /**
     * Reglas de validación para la creación de una Plantilla.
     *
     * @return array<string, mixed>
     */
    public static function rules(): array
    {
        return [
            'id_institucion' => [
                'sometimes',
                'integer',
                'exists:institucion,id',
                'nullable'
            ],
            'codigo_tipoprograma' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro,id',
                'nullable'
            ],
            'nombre' => [
                'sometimes',
                'string',
                'max:100',
                'nullable'
            ],
            'tipo_disenio' => [
                'sometimes',
                'string',
                'max:100',
                'nullable'
            ],
            'disenio_default' => [
                'sometimes',
                'string',
                'max:100',
                'nullable'
            ],
            'descripcion' => [
                'sometimes',
                'string',
                'max:150',
                'nullable'
            ],
            'path_imagen_publica' => [
                'sometimes',
                'file',
                'image',
                'max:2048',
                'nullable'
            ],
            'path_pdf_fondo' => [
                'sometimes',
                'file',
                'mimes:pdf',
                'max:10240',
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
                'sometimes',
                'boolean',
                'nullable'
            ],
        ];
    }
}
