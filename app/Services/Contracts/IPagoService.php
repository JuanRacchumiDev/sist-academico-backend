<?php

namespace App\Services\Contracts;

use App\DTOs\Pago\PagoCreateDTO;
use App\Models\Pago;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IPagoService
{
    public function getAllPagos(?array $searchParams = null): Collection;

    public function getAllPagosWithFilters(array $filters, int $perPage): LengthAwarePaginator;

    public function getMatriculaPDF(array $filters): array;

    public function getPagoModuloPDF(array $filters): array;

    public function getPagoById(int $id): ?Pago;

    public function generarConstancia(int $idPago);

    public function createPago(PagoCreateDTO $pagoCreateDTO): Pago;
}
