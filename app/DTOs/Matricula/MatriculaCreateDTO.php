<?php
namespace App\DTOs\Matricula;

use Spatie\LaravelData\Data;

class MatriculaCreateDTO extends Data
{
    public function __construct(
        public int $id_persona,
        public int $id_estadomatricula,
        public array $programas,
        public string $fecha_matricula,
        public bool $estado,
        public ?string $fecha_retiro = null,
        public ?string $fecha_reserva = null,
        public ?string $fecha_anula = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
    ){}

    public static function rules(): array
    {
        return [
            'id_persona' => [
                'required',
                'integer',
                'exists:persona,id'
            ],
            'id_estadomatricula' => [
                'required',
                'integer',
                'exists:detalle_parametro,codigo'
            ],
            'programas' => [
                'required',
                'array',
                'min:1'
            ],
            'programas.*' => [
                'required',
                'integer',
                'exists:programa,id'
            ],
            'fecha_matricula' => [
                'required',
                'string'
            ],
            'fecha_retiro' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'fecha_reserva' => [
                'sometimes',
                'string',
                'nullable'
            ],
            'fecha_anula' => [
                'sometimes',
                'string',
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
                'required',
                'boolean'
            ]
        ];
    } 
}