<?php
namespace App\DTOs\Modulo;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;

class ModuloUpdateDTO extends Data {
    public function __construct(
        public ?int $id_programa,
        public ?string $titulo,
        public ?string $titulo_url,
        public ?int $orden,
        public ?bool $estado = true,
        public ?string $descripcion = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null
    ){}

    public static function rules(): array
    {
        return [
            'id_programa' => [
                'sometimes',
                'integer',
                'exists:programa:id',
                'nullable'
            ],
            'titulo' => [
                'sometimes',
                'string',
                'max:100',
                Rule::unique('modulo', 'titulo'),
                'nullable'
            ],
            'titulo_url' => [
                'sometimes',
                'string',
                'max:120',
                Rule::unique('modulo', 'titulo_url'),
                'nullable'
            ],
            'descripcion' => [
                'sometimes',
                'string',
                'max:150',
                'nullable'
            ],
            'orden' => [
                'sometimes',
                'integer',
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
            ],
        ];
    }
}