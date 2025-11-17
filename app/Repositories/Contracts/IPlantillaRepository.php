<?php
namespace App\Repositories\Contracts;

use App\Models\Plantilla;
use Illuminate\Database\Eloquent\Collection;

interface IPlantillaRepository
{
    /**
     * Obtener todas las plantilla
     * @return Collection<int, Plantilla>
     */
    public function getAll(?array $searchParams = null): Collection;

    /**
     * Obtener una plantilla por ID
     * @param int $id
     * @return Plantilla|null
     */
    public function findById(int $id): ?Plantilla;

    /**
     * Crea una plantilla
     * @param array<string, mixed> $data
     * @return Plantilla
     */
    public function create(array $data): Plantilla;

    /**
     * Actualizar datos de una plantilla
     * @param int $id
     * @param array<string, mixed> $data
     * @return Plantilla|null
     */
    public function update(int $id, array $data): ?Plantilla;

    /**
     * Eliminar una plantilla por su ID
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}