<?php

namespace App\Repositories\Contracts;

use App\Models\DetalleParametro;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface IDetalleParametroRepository
{
    /**
     * Obtiene el detalle de un parámetro por clase
     * @param int $parametro_clase
     * @return Collection<int, DetalleParametro>
     */
    public function getAllByClase(int $parametro_clase): Collection;

    /**
     * Obtiene DetalleParametros aplicando filtros dinámicos
     * @param array<string, mixed> $filters
     * @return Collection<int, DetalleParametro>
     */
    public function getAllFiltered(array $filters): Collection;

    /**
     * Obtiene DetalleParametros aplicando filtros dinámicos
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllFilteredPaginate(array $filters, int $perPage): LengthAwarePaginator;

    /**
     * Obtener un detalle de parámetro por codigo
     * @param int $codigo
     * @return DetalleParametro|null
     */
    public function findByCodigo(int $codigo): ?DetalleParametro;

    /**
     * Obtener un detalle de parámetro por nombre_url
     * @param string $nombreUrl
     * @return DetalleParametro|null
     */
    public function findByNombreUrl(int $parametroClase, string $nombreUrl): ?DetalleParametro;

    /**
     * Obtener un detalle de parámetro por codigo y parametro_clase
     * @param int $parametro_clase 
     * @param int $codigo
     * @return DetalleParametro|null
     */
    // public function findByClaseAndCodigo(int $parametro_clase, int $codigo): ?DetalleParametro;

    /**
     * Obtener un detalle de parámetro por clase y nombreUrl
     * @param int $parametro_clase
     * @param string $nombreUrl
     * @return DetalleParametro|null
     */
    // public function findByClaseAndNombreUrl(int $parametro_clase, string $nombreUrl): ?DetalleParametro;

    /**
     * Crea un detalle parámetro
     * @param array<string, mixed> $data
     * @return DetalleParametro
     */
    public function create(array $data): DetalleParametro;

    /**
     * Actualizar datos de una detalle de parámetro
     * @param int $codigo
     * @param array<string, mixed> $data
     * @return DetalleParametro|null
     */
    public function update(int $codigo, array $data): ?DetalleParametro;

    /**
     * Eliminar una detalle de parámetro por codigo
     * @param int $codigo
     * @return bool
     */
    public function delete(int $codigo): bool;
}
