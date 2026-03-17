<?php
namespace App\DTOs\Adjunto;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class AdjuntoUpdateDTO extends Data
{
    public function __construct(
        public ?int $id_programa,
        public ?int $id_modulo,
        public ?int $id_institucion,
        public ?string $titulo,
        public ?string $titulo_url,
        public ?string $descripcion = null,
        public ?string $filename,
        public ?string $originalname,
        public ?string $filepath,
        public ?string $mimetype,
        public ?int $size,
        public ?bool $es_descargable,
        public ?bool $es_visible,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
        public ?bool $estado
    ){}

    public static function rules(): array
    {
        $adjuntoId = request()->route('adjunto');

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
                'sometimes',
                'string',
                'max:100',
                Rule::unique('adjunto', 'titulo')->ignore($adjuntoId),
                'nullable'
            ],
            'titulo_url' => [
                'sometimes',
                'string',
                'max:120',
                Rule::unique('adjunto', 'titulo_url')->ignore($adjuntoId),
                'nullable'
            ],
            'descripcion' => [
                'sometimes',
                'string',
                'max:150',
                'nullable'
            ],
            'filename' => [
                'sometimes',
                'string',
                'max:120',
                Rule::unique('adjunto', 'filename')->ignore($adjuntoId),
                'nullable'
            ],
            'originalname' => [
                'sometimes',
                'string',
                'max:180',
                Rule::unique('adjunto', 'originalname')->ignore($adjuntoId),
                'nullable'
            ],
            'filepath' => [
                'sometimes',
                'string',
                'max:150',
                Rule::unique('adjunto', 'filepath')->ignore($adjuntoId),
                'nullable'
            ],
            'mimetype' => [
                'sometimes',
                'string',
                'max:20',
                'nullable'
            ],
            'size' => [
                'sometimes',
                'integer',
                'nullable'
            ],
            'es_descargable' => [
                'sometimes',
                'boolean',
                'nullable'
            ],
            'es_visible' => [
                'sometimes',
                'boolean',
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
                'sometimes',
                'boolean',
                'nullable'
            ]
        ];
    }
}