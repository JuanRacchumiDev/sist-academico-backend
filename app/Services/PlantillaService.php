<?php
namespace App\Services;

use App\DTOs\Plantilla\PlantillaCreateDTO;
use App\DTOs\Plantilla\UploadPlantillaDTO;
use App\Models\Plantilla;
use App\Repositories\Contracts\IPlantillaRepository;
use App\Services\Contracts\IPlantillaService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PlantillaService implements IPlantillaService {
    protected IPlantillaRepository $plantillaRepository;

    public function __construct(IPlantillaRepository $plantillaRepository)
    {
        $this->plantillaRepository = $plantillaRepository;
    }

    public function getAllPlantillas(?array $searchParams = null): Collection
    {
        return $this->plantillaRepository->getAll($searchParams);
    }

    public function getPlantillaById(int $id): ?Plantilla
    {
        return $this->plantillaRepository->findById($id);
    }

    public function createPlantilla(PlantillaCreateDTO $plantillaCreateDTO): Plantilla
    {
        // Asegurarse de que el 'path' es un objeto UploadedFile
        if ($plantillaCreateDTO->path instanceof UploadedFile) {
     
            // Obtener el nombre original del archivo
            $originalName = $plantillaCreateDTO->path->getClientOriginalName();
            
            // Almacenar el archivo PDF
            // $path = $plantillaCreateDTO->path->store('plantillas', 'public');
            $path = $plantillaCreateDTO->path->storeAs(
                'plantillas',
                $originalName,
                'public'
            );
            
            // Reemplazar el objeto UploadedFile con la ruta de almacenamiento en el DTO
            $plantillaCreateDTO->path = $path;
            // $plantillaCreateDTO->path = $fullname;
        } else {
            $plantillaCreateDTO->path = null;
        }

        // Persistir en la base de datos a través del Repositorio
        $plantillaData = $plantillaCreateDTO->toArray();

        return $this->plantillaRepository->create($plantillaData);
    }
}