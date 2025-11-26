<?php
namespace App\Repositories\Contracts;

use App\Models\Pago;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IPagoRepository
{
    /**
     * Obtener todos los pagos
     * @param array<string, mixed>|null $searchParams
     * @return Collection<int, Pago>
     */
    public function getAll(?array $searchParams = null): Collection;

    /**
     * Obtiene todos los pagos
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator;

    /**
     * Obtener pago por id
     * @param int $id
     * @return Pago|null
     */
    public function findById(int $id): ?Pago;

    /**
     * Crea un pago
     * @param array<string, mixed> $data
     * @return Pago
     */
    public function create(array $data): Pago;

    /**
     * Actualizar datos de un pago
     * @param int $id
     * @param array<string, mixed> $data
     * @return Pago|null
     */
    public function update(int $id, array $data): ?Pago;

    /**
     * Eliminar un pago por su ID
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}