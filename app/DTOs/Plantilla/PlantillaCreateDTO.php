<?php

namespace App\DTOs\Plantilla;

use Spatie\LaravelData\Data;
use Illuminate\Http\UploadedFile;

class PlantillaCreateDTO extends Data
{
    public function __construct(
        public string $nombre,
        public ?int $id_institucion = null,
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
        public bool $estado,
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
            'nombre' => [
                'required',
                'string',
                'max:100'
            ],
            'descripcion' => [
                'sometimes',
                'string',
                'max:150',
                'nullable'
            ],
            'path_imagen_fondo' => [
                'sometimes',
                'file',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
                'nullable'
            ],
            'path_imagen_publica' => [
                'sometimes',
                'file',
                'image',
                'mimes:jpg,jpeg,png',
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
                'boolean'
            ],
        ];
    }
}
