<?php
namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

interface IUserRepository {

    public function get(array $filters = [], ?int $perPage = null);
    public function findOne(array $filters): ?User;
    public function create(array $data): User;
    public function update(int $id, array $data): ?User;
    public function delete(int $id): bool;

    // public function getAll(?array $searchParams = null): Collection;
    // public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator;
    // public function findById(int $id): ?User;
    // public function create(array $data): User;
    // public function update(int $id, array $data): ?User;
    // public function delete(int $id): bool;

    // public function getAll(): Collection;
    // public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator;
    // public function findByName(string $name): ?User;
    // public function findAllByEmail(string $email): Collection;
    // public function findByEmail(string $email): ?User;
    // public function findByNameAndEmail(string $name, string $email): ?User;
    // public function findById(int $id): ?User;
    // public function findByIdPersona(int $idPersona): ?User;
    // public function findAllByIdPerfil(int $idPerfil): Collection;
    // public function findByNameAndEmailAndPerfil(string $name, string $email, int $idPerfil): ?User;
    // public function create(array $data): User;
    // public function update(int $id, array $data): ?User;
    // public function delete(int $id): bool;
}