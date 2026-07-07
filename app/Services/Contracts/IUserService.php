<?php

namespace App\Services\Contracts;

use App\DTOs\User\UserCreateDTO;
use App\DTOs\User\UserUpdateDTO;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IUserService
{

    public function getAllUsers(array $filters = [], int $perPage = 10);
    public function getAllUsersWithFilters(array $filters, int $perPage): LengthAwarePaginator;
    public function getUserById(int $id): ?User;
    public function getUserByParams(array $filters = []);
    public function createUser(UserCreateDTO $dto): User;
    public function updateUser(int $id, UserUpdateDTO $dto): ?User;
}
