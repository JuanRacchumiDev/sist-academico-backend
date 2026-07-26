<?php

namespace App\DTOs\Certificado;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class CertificadoUpdateDTO extends Data
{
    public function __construct(
        public ?int $id_persona = null,
        public ?int $id_tipocertificado = null,
        public ?int $id_plantilla = null,
        public ?int $id_programa = null,
        public ?int $id_modulo = null,
        public ?string $codigo_verificacion = null,
        public ?string $codigo_qr_path = null,
        public ?string $path_file = null,
        public ?string $filename = null,
        public ?string $nombre_impresion = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
        public ?bool $estado = null
    ) {}

    public static function rules(): array
    {
        $certificadoId = request()->route('certificado');

        return [
            'id_persona' => [
                'sometimes',
                'integer',
                'exists:persona,id',
                'nullable'
            ],
            'id_tipocertificado' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro,codigo',
                'nullable'
            ],
            'id_plantilla' => [
                'sometimes',
                'integer',
                'exists:plantilla,id',
                'nullable'
            ],
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
            'codigo_verificacion' => [
                'sometimes',
                'string',
                'max:12',
                Rule::unique('certificado', 'codigo_verificacion')->ignore($certificadoId),
                'nullable'
            ],
            'codigo_qr_path' => [
                'sometimes',
                'string',
                'max:350',
                'nullable'
            ],
            'path_file' => [
                'sometimes',
                'string',
                'max:350',
                'nullable'
            ],
            'filename' => [
                'sometimes',
                'string',
                'max:150',
                'nullable'
            ],
            'nombre_impresion' => [
                'sometimes',
                'string',
                'max:150',
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
            ]
        ];
    }
}
