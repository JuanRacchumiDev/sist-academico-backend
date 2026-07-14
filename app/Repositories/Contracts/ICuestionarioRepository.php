<?php

namespace App\Repositories\Contracts;

use App\Models\Cuestionario;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ICuestionarioRepository
{
    public function getAll(?array $searchParams = null): Collection;
    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator;
    public function findById(int $id): ?Cuestionario;
    public function create(array $data): Cuestionario;
    public function update(int $id, array $data): ?Cuestionario;
    public function delete(int $id): bool;
}
