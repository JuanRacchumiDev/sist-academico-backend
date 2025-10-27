<?php
namespace App\Services\Contracts;

use App\DTOs\User\UserCreateDTO;
use App\DTOs\User\UserUpdateDTO;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IUserService {
    /**
     * Obtiene todos los usuarios
     * @return Collection<int, User>
     */
    public function getAllUsers(): Collection;

    /**
     * Obtiene todos los usuarios con filtros aplicados
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator<User>
     */
    public function getAllUsersWithFilters(array $filters, int $perPage): LengthAwarePaginator;

    /**
     * Obtiene un usuario por ID
     * @param int $id
     * @return User|null
     */
    public function getUserById(int $id): ?User;

    /**
     * Obtiene un usuario por email
     * @param string $email
     * @return User|null
     */
    public function getUserByEmail(string $email): ?User;

    /**
     * Valida las credenciales y maneja el inicio de sesión
     * @param string $email
     * @param string $passwors
     * @param int|null $idPerfil
     * @return array<string, mixed>
     */
    public function login(string $email, string $password, ?int $idPerfil = null): array;

    /**
     * Crea un nuevo usuario
     * @param UserCreateDTO $userCreateDTO
     * @return User
     */
    public function createUser(UserCreateDTO $userCreateDTO): User;

    /**
     * Actualiza un usuario existente
     * @param UserUpdateDTO $userUpdateDTO
     * @return User|null
     */
    public function updateUser(UserUpdateDTO $userUpdateDTO): ?User;

    /**
     * Elimina un usuario
     * @param int $id
     * @return bool
     */
    public function deleteUser(int $id): bool;
}