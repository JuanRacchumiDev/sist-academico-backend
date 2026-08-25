<?php

namespace App\Services;

use App\DTOs\Plantilla\PlantillaCreateDTO;
use App\DTOs\Plantilla\PlantillaUpdateDTO;
use App\Models\Plantilla;
use App\Repositories\Contracts\IPlantillaRepository;
use App\Services\Contracts\IPlantillaService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PlantillaService implements IPlantillaService
{
    protected IPlantillaRepository $plantillaRepository;

    public function __construct(IPlantillaRepository $plantillaRepository)
    {
        $this->plantillaRepository = $plantillaRepository;
    }

    public function getAllPlantillas(?array $searchParams = null): Collection
    {
        return $this->plantillaRepository->getAll($searchParams);
    }

    public function getAllPlantillasWithFilters(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->plantillaRepository->getAllFiltered($filters, $perPage);
    }

    public function getPlantillaById(int $id): ?Plantilla
    {
        return $this->plantillaRepository->findById($id);
    }

    public function createPlantilla(PlantillaCreateDTO $plantillaCreateDTO): Plantilla
    {
        $data = $plantillaCreateDTO->toArray();

        // Guardar imagen de fondo
        if ($plantillaCreateDTO->path_imagen_fondo instanceof UploadedFile) {
            $data['path_imagen_fondo'] = $this->storePlantillaFile(
                file: $plantillaCreateDTO->path_imagen_fondo,
                idInstitucion: $plantillaCreateDTO->id_institucion,
                nombrePlantilla: $plantillaCreateDTO->nombre,
                subfolder: 'fondos'
            );
        }

        // Guardar imagen pública
        if ($plantillaCreateDTO->path_imagen_publica instanceof UploadedFile) {
            $data['path_imagen_publica'] = $this->storePlantillaFile(
                file: $plantillaCreateDTO->path_imagen_publica,
                idInstitucion: $plantillaCreateDTO->id_institucion,
                nombrePlantilla: $plantillaCreateDTO->nombre,
                subfolder: 'publicas'
            );
        }

        // Guardar PDF de fondo
        if ($plantillaCreateDTO->path_pdf_fondo instanceof UploadedFile) {
            $data['path_pdf_fondo'] = $this->storePlantillaFile(
                file: $plantillaCreateDTO->path_pdf_fondo,
                idInstitucion: $plantillaCreateDTO->id_institucion,
                nombrePlantilla: $plantillaCreateDTO->nombre,
                subfolder: 'docs'
            );
        }

        return $this->plantillaRepository->create($data);
    }

    public function updatePlantilla(int $id, PlantillaUpdateDTO $plantillaUpdateDTO): ?Plantilla
    {
        $plantilla = $this->plantillaRepository->findById($id);

        if (!$plantilla) {
            return null;
        }

        $data = array_filter($plantillaUpdateDTO->toArray(), fn($value) => $value !== null);

        // Determinar ID institución y Nombre para las rutas
        $idInstitucion = $plantillaUpdateDTO->id_institucion ?? $plantilla->id_institucion;
        $nombrePlantilla = $plantillaUpdateDTO->nombre ?? $plantilla->nombre;

        // Actualizar archivo PDF si se envía uno nuevo
        if ($plantillaUpdateDTO->path_imagen_fondo instanceof UploadedFile) {
            if ($plantilla->path_imagen_fondo) {
                Storage::disk('public')->delete($plantilla->path_imagen_fondo);
            }
            $data['path_imagen_fondo'] = $this->storePlantillaFile(
                file: $plantillaUpdateDTO->path_imagen_fondo,
                idInstitucion: $idInstitucion,
                nombrePlantilla: $nombrePlantilla,
                subfolder: 'docs'
            );
        }

        // Actualizar imagen si se envía una nueva
        if ($plantillaUpdateDTO->path_imagen_publica instanceof UploadedFile) {
            if ($plantilla->path_imagen_publica) {
                Storage::disk('public')->delete($plantilla->path_imagen_publica);
            }
            $data['path_imagen_publica'] = $this->storePlantillaFile(
                file: $plantillaUpdateDTO->path_imagen_publica,
                idInstitucion: $idInstitucion,
                nombrePlantilla: $nombrePlantilla,
                subfolder: 'images'
            );
        }

        return $this->plantillaRepository->update($id, $data);
    }

    public function deletePlantilla(int $id): bool
    {
        $plantilla = $this->plantillaRepository->findById($id);

        if (!$plantilla) {
            return false;
        }

        if ($plantilla->path_imagen_fondo) {
            Storage::disk('public')->delete($plantilla->path_imagen_fondo);
        }

        if ($plantilla->path_imagen_publica) {
            Storage::disk('public')->delete($plantilla->path_imagen_publica);
        }

        return $this->plantillaRepository->delete($id);
    }

    /**
     * Almacena el archivo con la estructura:
     * storage/app/public/0000{id_institucion}/plantillas/{subfolder}/{nombre-plantilla}.{ext}
     */
    private function storePlantillaFile(
        UploadedFile $file,
        ?int $idInstitucion,
        string $nombrePlantilla,
        string $subfolder = 'docs'
    ): string {
        // Formatear código de institución a 5 dígitos (ej. 1 -> 00001)
        $formattedInstId = $idInstitucion !== null
            ? str_pad((string) $idInstitucion, 5, '0', STR_PAD_LEFT)
            : 'general';

        // Normalizar nombre del archivo para evitar espacios o caracteres especiales
        $fileName = Str::slug($nombrePlantilla) . '_' . time() . '.' . $file->getClientOriginalExtension();

        // Estructura de directorio
        $folderPath = "{$formattedInstId}/plantillas/{$subfolder}";

        // Almacenar en el disco 'public'
        return $file->storeAs($folderPath, $fileName, 'public');
    }
}
