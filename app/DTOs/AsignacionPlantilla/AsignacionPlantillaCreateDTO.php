<?php
namespace App\DTOs\AsignacionPlantilla;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;
use Illuminate\Support\Str;

class AsignacionPlantillaCreateDTO extends Data
{
    public function __construct(
        public int $id_plantilla,
        public int $id_evento
    ){}

    public static function rules(): array
    {
        return [
            'id_plantilla' => [
                'required',
                'integer',
                'exists:plantilla,id'
            ],
            'id_evento' => [
                'required',
                'integer',
                'exists:evento,id'
            ]
        ];
    } 
}