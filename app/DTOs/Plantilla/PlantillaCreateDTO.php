<?php
namespace App\DTOs\Plantilla;

use Spatie\LaravelData\Data;
use Illuminate\Http\UploadedFile;

class PlantillaCreateDTO  extends Data
{
    public function __construct(
        public string $nombre,
        public UploadedFile|string|null $path, // Puede ser el objeto UploadedFile al crear o el string del path al actualizar/obtener
        public ?string $descripcion, // Nullable en la migración
        public ?string $user_crea,
        public ?string $user_actualiza,
        public ?string $user_elimina,
        public bool $estado = true, // Con valor por defecto
    ) {}
}