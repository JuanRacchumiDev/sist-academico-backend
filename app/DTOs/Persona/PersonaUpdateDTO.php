<?php
namespace App\DTOs\Persona;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class PersonaUpdateDTO extends Data
{
    public function __construct(
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
    ){}

    public static function rules(): array
    {
        $personaId = request()->route('persona');

        return [
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
            'sexo' => [
                'sometimes',
                'string',
                'exists:detalle_parametro,codigo'
            ],
        ];
    }
}