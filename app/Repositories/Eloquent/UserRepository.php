<?php
namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\IUserRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements IUserRepository {
    public function getAll(): Collection
    {
        /**
         * Obtiene todos los usuarios
         * @return Collection<int, User>
         */
        return User::with([
            'perfil',
            'persona'
        ])->get();
    }

    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = User::with([
            'perfil',
            'persona'
        ]);

        if (isset($filters['name'])) {
            $query->where('name', 'like', '%'.$filters['name'].'%');
        }

        if (isset($filters['email'])) {
            $query->where('email', 'like', '%'.$filters['email'].'%');
        }

        if (isset($filters['id_perfil'])) {
            $query->where('id_perfil', $filters['id_perfil']);
        }

        if (isset($filters['id_persona'])) {
            $query->where('id_persona', $filters['id_persona']);
        }

        if (isset($filters['estado'])) {
            $query->where('estado', (bool)$filters['estado']);
        }

        return $query->paginate($perPage);
    }

    /**
     * Obtiene un usuario por nombre
     * @param string $name
     * @return User|null
     */
    public function findByName(string $name): ?User
    {
        return User::with([
            'perfil',
            'persona'
        ])->where('name', $name)->first();
    }

    /**
     * Obtiene usuarios por email
     * @param string $email
     * @return Collection<int, User>
     */
    public function findAllByEmail(string $email): Collection
    {
        return User::with([
            'perfil',
            'persona'
        ])->where('email', $email)->get();
    }

    /**
     * Obtiene un usuario por email
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return User::with([
            'perfil',
            'persona'
        ])->where('email', $email)->first();
    }

    /**
     * Obtiene todos los usuarios por perfil
     * @param int $idPerfil
     * @return Collection<int, User>
     */
    public function findAllByIdPerfil(int $idPerfil): Collection
    {
        return User::with([
            'perfil',
            'persona'
        ])->where('id_perfil', $idPerfil)->get();
    }

    /**
     * Obtiene un usuario por nombre y email
     * @param string $name
     * @param string $email
     * @return User|null
     */
    public function findByNameAndEmail(string $name, string $email): ?User
    {
        return User::with([
            'perfil',
            'persona'
        ])->where('mame', $name)
            ->where('email', $email)
            ->first();
    }

    /**
     * Obtiene un usuario por ID
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User
    {
        return User::with([
            'perfil',
            'persona'
        ])->find($id);
    }

    /**
     * Obtiene un usuario por persona
     * @param int $idPersona
     * @return User|null
     */
    public function findByIdPersona(int $idPersona): ?User
    {
        return User::with([
            'perfil',
            'persona'
        ])->where('id_persona', $idPersona)->first();
    }

    /**
     * Obtiene un usuario por nombre de usuario, email y perfil
     * @param string $name
     * @param string $email
     * @param int $idPerfil
     * @return User|null
     */
    public function findByNameAndEmailAndPerfil(string $name, string $email, int $idPerfil): ?User
    {
        return User::with([
            'perfil',
            'persona'
        ])->where('name', $name)
            ->where('email', $email)
            ->where('id_perfil', $idPerfil)
            ->first();        
    }

    /**
     * Crea un nuevo usuario
     * @param array <string, mixed> $data
     * @return User
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Actualizar un usuario existente
     * @param int $id
     * @param array <string, mixed> $data
     * @return  User|null
     */
    public function update(int $id, array $data): ?User
    {
        $user = $this->findById($id);

        if ($user) {
            $user->update($data);
            return $user;
        }

        return null;
    }

    /**
     * Elimina un user por ID
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $user = $this->findById($id);

        if ($user) {
            return $user->delete();
        }
        
        return false;
    }
}