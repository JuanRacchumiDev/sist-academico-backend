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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PlantillaService implements IPlantillaService
{
    protected IPlantillaRepository $plantillaRepository;

    public function __construct(IPlantillaRepository $plantillaRepository)
    {
        $this->plantillaRepository = $plantillaRepository;
    }

    public function getAllPlantillas(?array $searchParams = null): Collection
    {
        Log::info('Validando searchParams in PlantillaService', [
            'searchParams' => $searchParams
        ]);

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

    public function obtenerPlantillaByParams(int $idInstitucion, int $idSucursal, int $codigoTipoPrograma, string $tipoDisenio, string $disenioDefault): ?Plantilla
    {
        return $this->plantillaRepository->findDuplicate($idInstitucion, $idSucursal, $codigoTipoPrograma, $tipoDisenio, $disenioDefault);
    }
}
