<?php
namespace App\DTOs\Matricula;

use Spatie\LaravelData\Data;

class MatriculaCreateDTO extends Data
{
    public function __construct(
        public int $id_alumno,
        public array $programas,
        public string $fecha_matricula,
        public bool $estado = true,
        public ?int $id_sede = null,
        public ?int $id_formapago = null,
        public ?int $id_estadopago = null,
        public ?int $id_metodopago = null,
        public ?int $id_estadomatricula = null,
        public ?string $numero_operacion = null,
        public ?int $monto = null,
        public ?string $nombre_alumno = null,
        public ?string $nombre_sede = null,
        public ?string $nombre_formapago = null,
        public ?string $nombre_estadopago = null,
        public ?string $nombre_metodopago = null,
        public ?string $nombre_estadomatricula = null,
        public ?float $pago_inicial = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
    ){}

    public static function rules(): array
    {
        return [
            'id_alumno' => [
                'required',
                'integer',
                'exists:persona,id'
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
            'id_sede' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro,codigo',
                'nullable'
            ],
            'id_formapago' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro,codigo',
                'nullable'
            ],
            'id_estadopago' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro,codigo',
                'nullable'
            ],
            'id_metodopago' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro,codigo',
                'nullable'
            ],
            'id_estadomatricula' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro,codigo',
                'nullable'
            ],
            'nombre_alumno' => [
                'sometimes',
                'string',
                'max:100',
                'nullable'
            ],
            'nombre_sede' => [
                'sometimes',
                'string',
                'max:60',
                'nullable'
            ],
            'nombre_formapago' => [
                'sometimes',
                'string',
                'max:60',
                'nullable'
            ],
            'nombre_estadopago' => [
                'sometimes',
                'string',
                'max:60',
                'nullable'
            ],
            'nombre_metodopago' => [
                'sometimes',
                'string',
                'max:60',
                'nullable'
            ],
            'nombre_estadomatricula' => [
                'sometimes',
                'string',
                'max:60',
                'nullable'
            ],
            'fecha_matricula' => [
                'required',
                'string'
            ],
            'pago_inicial' => [
                'sometimes',
                'numeric',
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
                'boolean'
            ]
        ];
    } 
}