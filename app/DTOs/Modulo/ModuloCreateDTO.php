<?php

namespace App\DTOs\Modulo;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class ModuloCreateDTO extends Data
{
    public function __construct(
        public string $titulo,
        public string $titulo_url,
        public int $orden,
        public bool $estado = true,
        public ?int $id_programa = null,
        public ?string $descripcion = null,
        public ?string $temario = null,
        public ?float $nota = null,
        public ?string $fecha_crea = null,
        public ?string $fecha_actualiza = null,
        public ?string $fecha_elimina = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null
    ) {}

    public static function rules(): array
    {
        return [
            'id_programa' => [
                'required',
                'integer',
                'exists:programa,id'
            ],
            'titulo' => [
                'required',
                'string',
                'max:100',
                Rule::unique('modulo', 'titulo')
            ],
            'titulo_url' => [
                'required',
                'string',
                'max:120',
                Rule::unique('modulo', 'titulo_url')
            ],
            'descripcion' => [
                'sometimes',
                'string',
                'max:150',
                'nullable'
            ],
            'temario' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'nota' => [
                'sometimes',
                'numeric',
                'min:0',
                'nullable'
            ],
            'orden' => [
                'required',
                'integer',
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
            ],
            'estado' => [
                'required',
                'boolean'
            ],
        ];
    }
}
