<?php
namespace App\Services\Contracts;

use App\DTOs\Pago\PagoCreateDTO;
use App\Models\Pago;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IPagoService {
    /**
     * Obtener todos los pagos
     * @param array<string, mixed>|null $searchParams
     * @return Collection<int, Pago>
     */
    public function getAllPagos(?array $searchParams = null): Collection;

    /**
     * Obtiene todos los pagos con filtros aplicados
     * @param array<string, mixed> $filters
     * @param int $perPage
     */
    public function getAllPagosWithFilters(array $filters, int $perPage): LengthAwarePaginator;

    /**
     * Obtiene un pago por ID
     * @param int $id
     * @return Pago|null
     */
    public function getPagoById(int $id): ?Pago;

    /**
     * Crear un nuevo pago
     * @param PagoCreateDTO $pagoCreateDTO
     * @return Pago
     */
    public function createPago(PagoCreateDTO $pagoCreateDTO): Pago;
}