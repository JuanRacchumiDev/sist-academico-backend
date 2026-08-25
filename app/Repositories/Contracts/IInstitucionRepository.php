<?php

namespace App\Repositories\Contracts;

use App\Models\Institucion;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface IInstitucionRepository
{
    public function getAll(): Collection;
    public function getAllFiltered(array $filters): Collection;
    public function getAllFilteredPaginate(array $filters, int $perPage): LengthAwarePaginator;
    public function findById(int $id): ?Institucion;
    public function create(array $data): Institucion;
    public function update(int $id, array $data): ?Institucion;
}
