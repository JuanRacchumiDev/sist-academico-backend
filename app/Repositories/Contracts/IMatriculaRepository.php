<?php
namespace App\Repositories\Contracts;

use App\Models\Matricula;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\DTOs\Matricula\MatriculaCreateDTO;

interface IMatriculaRepository
{
    public function getAll(?array $searchParams = null): Collection;

    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator;

    public function getUniqueForFilters(array $filters): ?Matricula;

    public function getFilePath(array $filters): string;

    public function getCertificadoData(array $filters): ?array;

    public function existsCertificado(array $filters): bool;

    public function savePDF(array $filters, string $pdfContent): void;

    public function getPDF(array $filters): string;

    public function findById(int $id): ?Matricula;

    public function create(MatriculaCreateDTO $dto): Matricula;

    public function update(int $id, array $data): ?Matricula;

    public function delete(int $id): bool;
}