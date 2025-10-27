<?php
namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IUserRepository {
    /**
     * Obtiene todos los usuarios
     * @return Collection<int, User>
     */
    public function getAll(): Collection;

    /**
     * Obtiene todos los usuarios
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator;

    /**
     * Obtiene un usuario por nombre
     * @param string $name
     * @return User|null
     */
    public function findByName(string $name): ?User;

    /**
     * Obtiene todos los usuarios por email
     * @param string $email
     * @return Collection<int, User>
     */
    public function findAllByEmail(string $email): Collection;

    /**
     * Obtiene un usuario por email
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;

    /**
     * Obtiene un usuario por nombre y email
     * @param string $name
     * @param string $email
     * @return User|null
     */
    public function findByNameAndEmail(string $name, string $email): ?User;

    /**
     * Obtiene un usuario por ID
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User;

    /**
     * Obtiene un usuario por id de persona
     * @param int $idPersona
     * @return User|null
     */
    public function findByIdPersona(int $idPersona): ?User;

    /**
     * Obtiene todo los usuarios por id de un perfil
     * @param int $idPerfil
     * @return Collection<int, User>
     */
    public function findAllByIdPerfil(int $idPerfil): Collection;

    /**
     * Obtiene un usuario por name, email y tipo perfil
     * @param string $name
     * @param string $email
     * @param int $idPerfil
     * @return User|null
     */
    public function findByNameAndEmailAndPerfil(string $name, string $email, int $idPerfil): ?User;

    /**
     * Crea un usuarii
     * @param array <string, mixed> $data
     * @return User
     */
    public function create(array $data): User;

    /**
     * Actualiza un usuario
     * @param int $id
     * @param array <string, mixed> $data
     * @return User|null
     */
    public function update(int $id, array $data): ?User;

    /**
     * Elimina un usuario por ID
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}