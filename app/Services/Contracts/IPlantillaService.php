<?php

namespace App\Services\Contracts;

use App\DTOs\Plantilla\PlantillaCreateDTO;
use App\DTOs\Plantilla\PlantillaUpdateDTO;
use App\Models\Plantilla;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IPlantillaService
{
    public function getAllPlantillas(?array $searchParams = null): Collection;

    public function getAllPlantillasWithFilters(array $filters, int $perPage): LengthAwarePaginator;

    public function getPlantillaById(int $id): ?Plantilla;

    public function obtenerPlantillaByParams(int $idInstitucion, int $idSucursal, int $codigoTipoPrograma, string $tipoDisenio, string $disenioDefault): ?Plantilla;
}
