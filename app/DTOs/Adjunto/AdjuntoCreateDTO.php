<?php
namespace App\DTOs\Adjunto;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class AdjuntoCreateDTO extends Data
{
    public function __construct(
        public string $titulo,
        public string $titulo_url,
        public string $filename,
        public string $originalname,
        public string $filepath,
        public string $mimetype,
        public int $size,
        public bool $es_descargable,
        public bool $es_visible,
        public bool $estado,
        public ?int $id_programa = null,
        public ?int $id_modulo = null,
        public ?int $id_institucion = null,
        public ?string $descripcion = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
    ){}

    public static function withDefaults(): array
    {
        return [
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
            'filename' => [
                'required',
                'string',
                'max:120'
            ],
            'originalname' => [
                'required',
                'string',
                'max:180'
            ],
            'filepath' => [
                'required',
                'string',
                'max:150'
            ],
            'mimetype' => [
                'required',
                'string',
                'max:20'
            ],
            'size' => [
                'required',
                'integer'
            ],
            'es_descargable' => [
                'required',
                'boolean'
            ],
            'es_visible' => [
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
                'required',
                'boolean'
            ]
        ];
    } 
}