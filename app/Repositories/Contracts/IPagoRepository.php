<?php

namespace App\Repositories\Contracts;

use App\Models\Pago;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IPagoRepository
{
    public function getAll(?array $searchParams = null): Collection;

    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator;

    public function getMatriculaData(array $filters);

    public function getFilePath(array $filters): string;

    public function existsPDF(array $filters): bool;

    public function savePDF(array $filters, string $pdfContent): void;

    public function getPDF(array $filters): string;

    public function getPagoModuloData(array $filters);

    public function getModulosPorPagar(int $idMatricula, int $totalModulos);

    public function getModulosPagados(int $idMatricula);

    public function getPagosByMatricula(int $idMatricula);

    public function findById(int $id): ?Pago;

    public function create(array $data): Pago;

    public function update(int $id, array $data): ?Pago;

    public function delete(int $id): bool;
}
