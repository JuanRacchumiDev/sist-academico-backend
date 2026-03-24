<?php
namespace App\Repositories\Contracts;

use App\Models\Adjunto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IAdjuntoRepository
{
    public function getAll(?array $searchParams): Collection;
    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator;
    public function findById(int $id): ?Adjunto;
    public function create(array $data): Adjunto;
    public function update(int $id, array $data): ?Adjunto;
    public function delete(int $id): bool;
}