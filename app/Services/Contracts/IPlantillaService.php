<?php
namespace App\Services\Contracts;

use App\DTOs\Plantilla\PlantillaCreateDTO;
use App\Models\Plantilla;
use Illuminate\Database\Eloquent\Collection;

interface IPlantillaService {
    public function getAllPlantillas(?array $searchParams = null): Collection;

    public function getPlantillaById(int $id): ?Plantilla;

    public function createPlantilla(PlantillaCreateDTO $plantillaCreateDTO): Plantilla;
}