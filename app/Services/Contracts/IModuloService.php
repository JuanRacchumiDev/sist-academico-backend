<?php
namespace App\Services\Contracts;

use App\DTOs\Modulo\ModuloCreateDTO;
use App\Models\Modulo;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IModuloService {
    /**
     * Obtener todos los módulos
     * @param array<string, mixed>|null $searchParams
     * @return Collection<int, Modulo>
     */
    public function getAllModulos(?array $searchParams = null): Collection;

    /**
     * Obtiene todos los módulos con filtros aplicados
     * @param array<string, mixed> $filters
     * @param int $perPage
     */
    public function getAllModulosWithFilters(array $filters, int $perPage): LengthAwarePaginator;

    /**
     * Obtener los módulos por programa
     * @param int $idPrograma
     * @return Collection<int, Modulo>
     */
    public function getAllModulosByPrograma(int $idPrograma): Collection;

    /**
     * Obtiene un módulo por ID
     * @param int $id
     * @return Modulo|null
     */
    public function getModuloById(int $id): ?Modulo;

    /**
     * Crear un nuevo módulo
     * @param ModuloCreateDTO $moduloCreateDTO
     * @return Modulo
     */
    public function createModulo(ModuloCreateDTO $moduloCreateDTO): Modulo;
}