<?php
namespace App\Services\Contracts;

use App\DTOs\User\UserCreateDTO;
use App\DTOs\User\UserUpdateDTO;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IUserService {

    public function getAllUsers(array $filters = [], int $perPage = 10);
    public function getUserById(int $id): ?User;
    public function getUserByParams(array $filters = []);
    public function createUser(UserCreateDTO $dto): User;
    public function updateUser(int $id, UserUpdateDTO $dto): ?User;

    // public function getAllUsers(): Collection;
    // public function getAllUsersWithFilters(array $filters, int $perPage): LengthAwarePaginator;
    // public function getUserById(int $id): ?User;
    // public function getUserByEmail(string $email): ?User;
    // public function login(string $email, string $password, ?int $idPerfil = null): array;
    // public function createUser(UserCreateDTO $userCreateDTO): User;
    // public function updateUser(UserUpdateDTO $userUpdateDTO): ?User;
    // public function deleteUser(int $id): bool;
}