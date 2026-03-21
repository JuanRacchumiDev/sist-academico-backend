<?php
namespace App\DTOs\Matricula;

use Spatie\LaravelData\Data;

class MatriculaUpdateDTO extends Data
{
    public function __construct(
        public ?int $id_persona,
        public ?int $id_estadomatricula,
        public ?int $id_institucion,
        public ?array $programas,
        public ?string $fecha_matricula,
        public ?bool $estado,
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
                'sometimes',
                'integer',
                'exists:persona,id',
                'nullable'
            ],
            'id_estadomatricula' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro,codigo',
                'nullable'
            ],
            'id_institucion' => [
                'sometimes',
                'integer',
                'exists:institucion,id',
                'nullable'
            ],
            'programas' => [
                'sometimes',
                'array',
                'min:1'
            ],
            'programas.*' => [
                'sometimes',
                'integer',
                'exists:programa,id'
            ],
            'fecha_matricula' => [
                'sometimes',
                'string',
                'nullable'
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