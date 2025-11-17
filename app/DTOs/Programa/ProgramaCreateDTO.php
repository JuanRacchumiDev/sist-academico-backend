<?php
namespace App\DTOs\Programa;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class ProgramaCreateDTO extends Data {
    public function __construct(
        public int $id_segmento,
        public string $sigla,
        public string $nombre,
        public string $duracion,
        public int $modulos,
        public int $creditos,
        public bool $is_vigente = true,
        public bool $estado = true,
        public UploadedFile|string|null $plan,
        public ?string $codigo_old = null,
    ){}
}