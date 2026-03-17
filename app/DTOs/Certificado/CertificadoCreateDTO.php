<?php
namespace App\DTOs\Certificado;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class CertificadoCreateDTO extends Data
{
    public function __construct(
        public int $id_persona,
        public int $id_tipocertificado,
        public string $path_file,
        public string $filename,
        public string $nombre_impresion,
        public bool $estado,
        public ?int $id_plantilla = null,
        public ?int $id_programa = null,
        public ?string $codigo_verificacion = null,
        public ?string $codigo_qr_path = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null
    ){}

    /**
     * Define los valores por defecto para los campos opcionales/booleanos
     */
    public static function withDefaults(): array
    {
        return [
            'estado' => true
        ];
    }

    public static function rules(): array
    {
        return [
            'id_persona' => [
                'required',
                'integer',
                'exists:persona,id'
            ],
            'id_tipocertificado' => [
                'required',
                'integer',
                'exists:detalle_parametro,codigo'
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
            'codigo_verificacion' => [
                'sometimes',
                'string',
                'max:12',
                Rule::unique('certificado', 'codigo_verificacion'),
                'nullable'
            ],
            'codigo_qr_path' => [
                'sometimes',
                'string',
                'max:350',
                Rule::unique('certificado', 'codigo_qr_path'),
                'nullable'
            ],
            'path_file' => [
                'required',
                'string',
                'max:350'
            ],
            'filename' => [
                'required',
                'string',
                'max:150'
            ],
            'nombre_impresion' => [
                'required',
                'string',
                'max:150'
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

