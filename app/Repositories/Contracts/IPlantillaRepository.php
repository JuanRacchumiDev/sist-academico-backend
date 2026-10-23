<?php

namespace App\Repositories\Contracts;

use App\Models\Plantilla;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IPlantillaRepository
{
    /**
     * Obtener todas las plantilla
     * @return Collection<int, Plantilla>
     */
    public function getAll(?array $searchParams = null): Collection;

    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator;

    /**
     * Obtener una plantilla por ID
     * @param int $id
     * @return Plantilla|null
     */
    public function findById(int $id): ?Plantilla;

    public function findDuplicate(int $idInstitucion, int $idSucursal, int $codigoTipoPrograma, string $tipoDisenio, string $disenioDefault): ?Plantilla;
}
