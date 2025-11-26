<?php
namespace App\DTOs\Modulo;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;
use Illuminate\Support\Str;

class ModuloCreateDTO extends Data {
    public function __construct(
        public int $id_programa,
        public string $titulo,
        public bool $estado = true,
        public ?int $orden = null,
        public ?string $titulo_url = null,
        public ?string $descripcion = null,
        public ?string $adjunto = null,
        public ?string $video = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null
    ){}

    /**
     * Manipula los datos de la validación
     */
    // public static function prepareForValidation(array $payload): array
    // {
    //     // Verificamos si el campo título está en la petición
    //     if (isset($payload['titulo'])) {
    //         $payload['titulo_url'] = Str::slug($payload['titulo']);
    //     }

    //     return $payload;
    // }

    public static function rules(): array
    {
        return [
            'id_programa' => [
                'required',
                'integer',
                'exists:programa:id'
            ],
            'titulo' => [
                'required',
                'string',
                'max:100',
                Rule::unique('modulo', 'titulo')
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
            'adjunto' => [
                'sometimes',
                'string',
                'max:150',
                'nullable'
            ],
            'video' => [
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
                'max:10',
                'nullable'
            ],
            'user_actualiza' => [
                'sometimes',
                'string',
                'max:10',
                'nullable'
            ],
            'user_elimina' => [
                'sometimes',
                'string',
                'max:10',
                'nullable'
            ],
            'estado' => [
                'required',
                'boolean'
            ],
        ];
    }
}