<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IUserRepository
{
    public function getAll(array $filters = []): Collection;
    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator;
    public function findOne(array $filters): ?User;
    public function findById(int $id): ?User;
    public function create(array $data): User;
    public function update(int $id, array $data): ?User;
    public function delete(int $id): bool;
}
