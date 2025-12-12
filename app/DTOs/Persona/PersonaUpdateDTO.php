<?php
namespace App\DTOs\Persona;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class PersonaUpdateDTO extends Data
{
    public function __construct(
        public ?int $id_tipodocumento = null,
        public ?string $numero_documento = null,
        public ?string $nombres = null,
        public ?string $apellido_paterno = null,
        public ?string $apellido_materno = null,
        public ?string $nombre_completo = null,
        public ?string $departamento = null,
        public ?string $provincia = null,
        public ?string $distrito = null,
        public ?string $direccion = null,
        public ?string $direccion_completa = null,
        public ?string $email = null,
        public ?string $telefono = null,
        public ?string $fecha_nacimiento = null,
        public ?string $estado_civil = null,
        public ?string $sexo = null,
        public ?string $ubigeo_reniec = null,
        public ?string $ubigeo_sunat = null,
        public ?string $ubigeo = null,
        public ?string $foto = null
    ){}

    public static function rules(int $id): array
    {
        return [
            'id_tipodocumento' => [
                'sometimes',
                'integer',
                'exists:detalle_parametro,codigo'
            ],
            'numero_documento' => [
                'sometimes',
                'string',
                'max:30',
                Rule::unique('persona', 'numero_documento')->ignore($id)
            ],
            'nombres' => [
                'sometimes',
                'string',
                'max:30'
            ],
            'apellido_paterno' => [
                'sometimes',
                'string',
                'max:30'
            ],
            'apellido_materno' => [
                'sometimes',
                'string',
                'max:30'
            ],
            'departamento' => [
                'sometimes',
                'string',
                'max:50',
                'nullable'
            ],
            'provincia' => [
                'sometimes',
                'string',
                'max:50',
                'nullable'
            ],
            'distrito' => [
                'sometimes',
                'string',
                'max:50',
                'nullable'
            ],
            'direccion' => [
                'sometimes',
                'string',
                'max:50',
                'nullable'
            ],
            'direccion_completa' => [
                'sometimes',
                'string',
                'max:150',
                'nullable'
            ],
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:60',
                Rule::unique('persona', 'email')->ignore($id)
            ],
            'nombre_completo' => [
                'sometimes',
                'string',
                'max:100'
            ],
            'fecha_nacimiento' => [
                'sometimes',
                'string',
                'max:10'
            ],
            'estado_civil' => [
                'sometimes',
                'string',
                'max:20'
            ],
            'sexo' => [
                'sometimes',
                'string'
            ],
            'telefono' => [
                'sometimes',
                'string',
                'max:13',
                'nullable'
            ],
            'ubigeo_reniec' => [
                'sometimes',
                'string',
                'max:12',
                'nullable'
            ],
        ];
    }
}