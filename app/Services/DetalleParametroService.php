<?php

namespace App\Services;

use App\DTOs\DetalleParametro\DetalleParametroCreateDTO;
use App\DTOs\DetalleParametro\DetalleParametroUpdateDTO;
use App\Models\DetalleParametro;
use App\Repositories\Contracts\IDetalleParametroRepository;
use App\Services\Contracts\IDetalleParametroService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Override;

class DetalleParametroService implements IDetalleParametroService
{
    protected IDetalleParametroRepository $repository;

    public function __construct(IDetalleParametroRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Obtiene todos los detalle de un parámetro
     * @param int $clase
     * @return Collection<int, DetalleParametro>
     */
    public function getAllByClase(int $clase): Collection
    {
        return $this->repository->getAllByClase($clase);
    }

    /**
     * Obtiene detalle parámetros filtrados
     * @param array<int, mixed> $filters
     * @return Collection<int, DetalleParametro>
     */
    public function getAllFiltered(array $filters): Collection
    {
        return $this->repository->getAllFiltered($filters);
    }

    /**
     * Obtiene detalle parámetros filtrados
     * @param array<int, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator<DetalleParametro>
     */
    public function getAllFilteredPaginate(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->repository->getAllFilteredPaginate($filters, $perPage);
    }

    public function getUniqueByParams(array $filters): ?DetalleParametro
    {
        $parametroClase = isset($filters['parametro_clase']) ? $filters['parametro_clase'] : null;
        $codigo = isset($filters['codigo']) ? $filters['codigo'] : null;
        $nombreUrl = isset($filters['nombre_url']) ? $filters['nombre_url'] : null;

        if ($codigo && !$nombreUrl) {
            return $this->repository->findByCodigo($codigo);
        } else if (!$codigo && $nombreUrl) {
            return $this->repository->findByNombreUrl($parametroClase, $nombreUrl);
        } else {
            return null;
        }
    }

    /**
     * Crea un detalle parámetro
     * @param DetalleParametroCreateDTO $detalleParametroCreateDTO
     * @return DetalleParametro
     */
    public function createDetalle(DetalleParametroCreateDTO $detalleParametroCreateDTO): DetalleParametro
    {
        $data = array_filter($detalleParametroCreateDTO->toArray(), fn($value) => !is_null($value));

        return $this->repository->create($data);
    }

    /**
     * Actualiza un detalle parámetro
     * @param int $id
     * @param DetalleParametroUpdateDTO $detalleParametroUpdateDTO
     * @return DetalleParametro|null
     */
    public function updateDetalle(int $id, DetalleParametroUpdateDTO $detalleParametroUpdateDTO): ?DetalleParametro
    {
        $data = array_filter($detalleParametroUpdateDTO->toArray(), fn($value) => !is_null($value));

        return $this->repository->update($id, $data);
    }

    /**
     * Elimina un detalle parámetro
     * @param int $codigo
     * @return bool
     */
    public function deleteDetalle(int $codigo): bool
    {
        return $this->repository->delete($codigo);
    }
}
