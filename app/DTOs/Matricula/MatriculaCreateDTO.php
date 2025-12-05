<?php
namespace App\DTOs\Matricula;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;
use Illuminate\Support\Str;

class MatriculaCreateDTO extends Data
{
    public function __construct(
        public int $id_alumno,
        public array $programas,
        public string $fecha_matricula,
        public bool $estado = true,
        public ?int $id_sede = null,
        // public ?int $id_programa = null,
        // public ?int $id_evento = null,
        public ?int $id_estadomatricula = null,
        public ?int $id_metodopago = null,
        public ?int $monto = null,
        public ?string $nombre_alumno = null,
        public ?string $nombre_sede = null,
        // public ?string $nombre_programa = null,
        // public ?string $nombre_evento = null,
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
            // 'id_programa' => [
            //     'sometimes',
            //     'integer',
            //     'exists:programa,id',
            //     'nullable'
            // ],
            // 'id_evento' => [
            //     'sometimes',
            //     'integer',
            //     'exists:evento,id',
            //     'nullable'
            // ],
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
            // 'nombre_programa' => [
            //     'sometimes',
            //     'string',
            //     'max:100',
            //     'nullable'
            // ],
            // 'nombre_evento' => [
            //     'sometimes',
            //     'string',
            //     'max:100',
            //     'nullable'
            // ],
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