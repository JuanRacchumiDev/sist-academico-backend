<?php
namespace App\DTOs\Persona;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class PersonaUpdateDTO extends Data
{
    public function __construct(
        // public ?int $id_tipodocumento = null,
        // public ?string $numero_documento = null,
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
        // public ?string $ubigeo_reniec = null,
        // public ?string $ubigeo_sunat = null,
        // public ?string $ubigeo = null,
        public ?string $fecha_nacimiento = null,
        public ?string $estado_civil = null,
        // public ?string $foto = null,
        public ?string $sexo = null,
        // public ?string $origen = "WEB",
        // public ?string $user_crea = null,
        // public ?string $user_actualiza = null,
        // public ?string $user_elimina = null,
        // public ?bool $estado = null,
        // public ?string $nombre_grupo = null
    ){}

    public static function rules(): array
    {
        $personaId = request()->route('persona');

        return [
            // 'id_tipodocumento' => [
            //     'sometimes',
            //     'integer',
            //     'exists:detalle_parametro,codigo'
            // ],
            // 'numero_documento' => [
            //     'sometimes',
            //     'string',
            //     'max:13',
            //     Rule::unique('persona', 'numero_documento')->ignore($personaId)
            // ],
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
                Rule::unique('persona', 'email')->ignore($personaId)
            ],
            'telefono' => [
                'sometimes',
                'string',
                'max:13'
            ],
            // 'ubigeo_reniec' => [
            //     'sometimes',
            //     'string',
            //     'max:12'
            // ],
            // 'ubigeo_sunat' => [
            //     'sometimes',
            //     'string',
            //     'max:12'
            // ],
            // 'ubigeo' => [
            //     'sometimes',
            //     'string',
            //     'max:12'
            // ],
            'fecha_nacimiento' => [
                'sometimes',
                'string',
                'max:10'
            ],
            'estado_civil' => [
                'sometimes',
                'string',
                'max:20',
                'exists:detalle_parametro,codigo'
            ],
            // 'foto' => [
            //     'sometimes',
            //     'string'
            // ],
            'sexo' => [
                'sometimes',
                'string',
                'exists:detalle_parametro,codigo'
            ],
            // 'user_crea' => [
            //     'sometimes',
            //     'string'
            // ],
            // 'user_actualiza' => [
            //     'sometimes',
            //     'string'
            // ],
            // 'user_elimina' => [
            //     'sometimes',
            //     'string'
            // ],
            // 'estado' => [
            //     'sometimes',
            //     'boolean'
            // ],
            // 'nombre_grupo' => [
            //     'sometimes',
            //     'string',
            //     Rule::exists('detalle_parametro', 'nombre_url')->where(function ($query) {
            //         $query->where('parametro_clase', 1010);
            //     }),
            // ],
        ];
    }
}