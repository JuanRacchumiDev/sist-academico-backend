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
        public string $email,
        public string $fecha_nacimiento,
        public string $estado_ciivl,
        public string $sexo,
        public string $origen = "WEB",
        public bool $estado = true, 
        public string $nombre_grupo,

        public ?string $departamento = null,
        public ?string $provincia = null,
        public ?string $distrito = null,
        public ?string $direccion = null,
        public ?string $direccion_completa = null,
        public ?string $telefono = null,
        public ?string $ubigeo_reniec = null,
        public ?string $ubigeo_sunat = null,
        public ?string $ubigeo = null,
        public ?string $foto = null,
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
                'required',
                'string',
                'email',
                'max:60',
                Rule::unique('persona', 'email')
            ],

            'nombre_grupo' => [
                'required',
                'string',
                // Asegura que el nombre_url exista y que pertenezca a la 'clase' Grupo (1010)
                Rule::exists('detalle_parametro', 'nombre_url')->where(function ($query) {
                    $query->where('parametro_clase', 1010);
                }),
            ],

            'fecha_nacimiento' => [
                'required',
                'string',
                'max:10'
            ],
            'estado_civil' => [
                'required',
                'string',
                'max:20',
                'exists:detalle_parametro,codigo'
            ],
            'sexo' => [
                'required',
                'string',
                'exists:detalle_parametro,codigo'
            ],
            'origen' => [
                'required',
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
            'foto' => [
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