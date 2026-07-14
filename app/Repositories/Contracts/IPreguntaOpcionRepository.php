<?php

namespace App\Repositories\Contracts;

use App\Models\PreguntaOpcion;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IPreguntaOpcionRepository
{
    public function getAll(?array $searchParams = null): Collection;
    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator;
    public function findById(int $id): ?PreguntaOpcion;
    public function create(array $data): PreguntaOpcion;
    public function update(int $id, array $data): ?PreguntaOpcion;
    public function delete(int $id): bool;
}
