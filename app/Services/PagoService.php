<?php
namespace App\Services;

use App\DTOs\Pago\PagoCreateDTO;
use App\Models\Pago;
use App\Repositories\Contracts\IPagoRepository;
use App\Services\Contracts\IPagoService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PagoService implements IPagoService {
    protected IPagoRepository $pagoRepository;

    public function __construct(IPagoRepository $pagoRepository)
    {
        $this->pagoRepository = $pagoRepository;
    }

    /**
     * Obtener todos los pagos
     * @param array<string, mixed>|null $searchParams
     * @return Collection<int, Pago>
     */
    public function getAllPagos(?array $searchParams = null): Collection
    {
        return $this->pagoRepository->getAll($searchParams);
    }

    /**
     * Obtiene todos los pagos con filtros aplicados
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */ 
    public function getAllPagosWithFilters(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->pagoRepository->getAllFiltered($filters, $perPage);
    }

    /**
     * Obtiene un pago por ID
     * @param int $id
     * @return Pago|null
     */
    public function getPagoById(int $id): ?Pago
    {
        return $this->pagoRepository->findById($id);
    }

    /**
     * Crear una nuevo pago
     * @param PagoCreateDTO $pagoCreateDTO
     * @return Pago
     */
    public function createPago(PagoCreateDTO $pagoCreateDTO): Pago
    {
        $data = array_filter($pagoCreateDTO->toArray(), fn($value) => !is_null($value));

        return $this->pagoRepository->create($data);
    }
}