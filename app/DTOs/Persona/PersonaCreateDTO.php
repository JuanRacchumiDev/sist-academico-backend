<?php
namespace App\DTOs\Persona;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class PersonaCreateDTO extends Data
{
    public function __construct(
        public int $id_tipodocumento,
        public string $numero_documento,
        public string $nombres,
        public string $apellido_paterno,
        public string $apellido_materno,
        public string $nombre_completo,
        public string $sexo,
        public string $origen,
        public string $nombre_grupo,
        public bool $estado, 
        public ?string $departamento = null,
        public ?string $provincia = null,
        public ?string $distrito = null,
        public ?string $direccion = null,
        public ?string $direccion_completa = null,
        public ?string $email = null,
        public ?string $telefono = null,
        public ?string $ubigeo_reniec = null,
        public ?string $ubigeo_sunat = null,
        public ?string $ubigeo = null,
        public ?string $fecha_nacimiento = null,
        public ?string $estado_civil = null,
        public ?string $foto = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null
    ){}

    /**
     * Define los valores por defecto para los campos opcionales/booleanos
     */
    // public static function withDefaults(): array
    // {
    //     return [
    //         'origen' => 'WEB'
    //     ];
    // }

    public static function rules(): array
    {
        return [
            'id_tipodocumento' => [
                'required',
                'integer',
                'exists:detalle_parametro,codigo'
            ],
            'numero_documento' => [
                'required',
                'string',
                'max:13',
                Rule::unique('persona', 'numero_documento')
            ],
            'nombres' => [
                'required',
                'string',
                'max:30'
            ],
            'apellido_paterno' => [
                'required',
                'string',
                'max:30'
            ],
            'apellido_materno' => [
                'required',
                'string',
                'max:30'
            ],
            'nombre_completo' => [
                'required',
                'string',
                'max:100'
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
                'max:60',
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
                Rule::unique('persona', 'email'),
                'nullable'
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
            'ubigeo_sunat' => [
                'sometimes',
                'string',
                'max:12',
                'nullable'
            ],
            'ubigeo' => [
                'sometimes',
                'string',
                'max:12',
                'nullable'
            ],
            'fecha_nacimiento' => [
                'sometimes',
                'string',
                'max:10',
                'nullable'
            ],
            'estado_civil' => [
                'sometimes',
                'string',
                'max:15',
                'nullable'
            ],
            'foto' => [
                'sometimes',
                'string',
                'max:100',
                'nullable'
            ],           
            'sexo' => [
                'required',
                'string',
                Rule::in(['M', 'F'])
            ],
            'origen' => [
                'required',
                'string',
                Rule::in(['API', 'WEB', 'APP'])
            ],
            'nombre_grupo' => [
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