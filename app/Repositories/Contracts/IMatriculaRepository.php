<?php
namespace App\Repositories\Contracts;

use App\Models\Matricula;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IMatriculaRepository
{
    public function getAll(?array $searchParams = null): Collection;

    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator;

    public function findById(int $id): ?Matricula;

    public function create(array $data): Matricula;

    public function update(int $id, array $data): ?Matricula;

    public function delete(int $id): bool;
}