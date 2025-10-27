<?php

namespace App\Repositories\Contracts;

use App\Models\Parametro;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IParametroRepository
{
    /**
     * Obtenerr todos los parámetros
     * @return Collection<int, Parametro>
     */
    public function getAll(): Collection;

    /**
     * Obtiene todos los parámetros
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator;

    /**
     * Obtener un parámetro por clase
     * @param int $clase
     * @return Parametro|null
     */
    public function findByClase(int $clase): ?Parametro;

    /**
     * Crea un parámetro
     * @param array<string, mixed> $data
     * @return Parametro
     */
    public function create(array $data): Parametro;

    /**
     * Actualizar datos de un parámetro
     * @param int $clase
     * @param array<string, mixed> $data
     * @return Parametro|null
     */
    public function update(int $clase, array $data): ?Parametro;

    /**
     * Eliminar un parámetro por su ID
     * @param int $clase
     * @return bool
     */
    public function delete(int $clase): bool;

    /**
     * Obtiene el próximo valor de parClase
     * @return int
     */
    public function getNextParClase(): int;

    /**
     * Obtener el detalle de un parámetro
     * @param int $clase
     * @return Parametro|null
     */
    public function getWithDetalle(int $clase): ?Parametro;
}