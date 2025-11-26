<?php
namespace App\Repositories\Contracts;

use App\Models\Modulo;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IModuloRepository
{
    /**
     * Obtener todos los módulos
     * @param array<string, mixed>|null $searchParams
     * @return Collection<int, Modulo>
     */
    public function getAll(?array $searchParams = null): Collection;

    /**
     * Obtiene todos los módulos
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator;

    /**
     * Obtener módulos por programa
     * @param int $id_programa
     * @return Collection<int, Modulo>
     */
    public function getAllByPrograma(int $id_programa): Collection;

    /**
     * Obtiene el número de orden actual
     * @param int $id_programa
     * @return int
     */
    public function getNumeroOrdenByPrograma(int $id_programa): int;

    /**
     * Obtener módulo por id
     * @param int $id
     * @return Modulo|null
     */
    public function findById(int $id): ?Modulo;

    /**
     * Crea un módulo
     * @param array<string, mixed> $data
     * @return Modulo
     */
    public function create(array $data): Modulo;
}