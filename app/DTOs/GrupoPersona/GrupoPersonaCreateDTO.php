<?php
namespace App\DTOs\GrupoPersona;

use Spatie\LaravelData\Data;

class GrupoPersonaCreateDTO extends Data
{
    public function __construct(
        public string $codigo_detalle_parametro,
        public int $id_persona,
        public ?int $id_institucion = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
    ){}

    public static function rules(): array
    {
        return [
            'codigo_detalle_parametro' => [
                'required',
                'string',
                'exists:detalle_parametro,codigo'
            ],
            'id_persona' => [
                'required',
                'integer',
                'exists:persona,id'
            ],
            'id_institucion' => [
                'sometimes',
                'integer',
                'exists:institucion,id',
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
        ];
    } 
}