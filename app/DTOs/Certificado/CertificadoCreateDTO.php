<?php
namespace App\DTOs\Certificado;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class CertificadoCreateDTO extends Data
{
    public function __construct(
        public int $id_evento,
        public int $id_persona,
        public int $id_tipocertificado,
        public int $id_plantilla,
        public string $path_codigo_qr,
        public string $path_file,
        public string $file_name,
        public string $nombre_impresion,
        public bool $estado = true,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
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
            'id_evento' => [
                'required',
                'integer',
                'exists:evento,id'
            ],
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
                'required',
                'integer',
                'exists:plantilla,id'
            ],
            'codigo' => [
                'required',
                'string',
                'max:12',
                Rule::unique('certificado', 'codigo')
            ],
            'path_codigo_qr' => [
                'required',
                'string'
            ],
            'path_file' => [
                'required',
                'string'
            ],
            'filename' => [
                'required',
                'string'
            ],
            'nombre_impresion' => [
                'required',
                'string'
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

