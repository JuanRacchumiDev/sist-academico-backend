<?php
namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\IUserRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

class UserRepository implements IUserRepository {
    
    public function get(array $filters = [], ?int $perPage = null)
    {
        $query = $this->applyFilters(User::query(), $filters);
        return $perPage ? $query->paginate($perPage) : $query->get();
    }

    public function findOne(array $filters): ?User
    {
        return $this->applyFilters(User::query(), $filters)->first();
    }

    public function create(array $data): User
    {
        $user = User::create($data);

        return $user;
    }

    public function update(int $id, array $data): ?User
    {
        $filters = ['id' => $id];

        $user = $this->applyFilters(User::query(), $filters)->first();

        if ($user) {
            $user->update($data);
            return $user;
        }

        return null;
    }

    public function delete(int $id): bool
    {
        $filters = ['id' => $id];

        $user = $this->applyFilters(User::query(), $filters)->first();

        if ($user) {
            return $user->delete();
        }

        return false;
    }

    private function applyFilters(Builder $query, array $filters): Builder
    {
        return $query->with(['perfil', 'persona'])
            ->when(isset($filters['id']), fn($q) => $q->where('id', $filters['id']))
            ->when(isset($filters['name']), fn($q) => $q->where('name', 'like', '%', $filters['name'].'%'))
            ->when(isset($filters['email']), fn($q) => $q->where('email', $filters['email']))
            ->when(isset($filters['id_perfil']), fn($q) => $q->where('id_perfil', $filters['id_perfil']))
            ->when(isset($filters['id_persona']), fn($q) => $q->where('id_persona', $filters['id_persona']))
            ->when(isset($filters['estado']), fn($q) => $q->where('estado', (bool)$filters['estado']));
    }

    // public function getAll(): Collection
    // {
    //     return User::with([
    //         'perfil',
    //         'persona'
    //     ])->get();
    // }

    // public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator
    // {
    //     $query = User::with([
    //         'perfil',
    //         'persona'
    //     ]);

    //     if (isset($filters['name'])) {
    //         $query->where('name', 'like', '%'.$filters['name'].'%');
    //     }

    //     if (isset($filters['email'])) {
    //         $query->where('email', 'like', '%'.$filters['email'].'%');
    //     }

    //     if (isset($filters['id_perfil'])) {
    //         $query->where('id_perfil', $filters['id_perfil']);
    //     }

    //     if (isset($filters['id_persona'])) {
    //         $query->where('id_persona', $filters['id_persona']);
    //     }

    //     if (isset($filters['estado'])) {
    //         $query->where('estado', (bool)$filters['estado']);
    //     }

    //     return $query->paginate($perPage);
    // }

    // public function findByName(string $name): ?User
    // {
    //     return User::with([
    //         'perfil',
    //         'persona'
    //     ])->where('name', $name)->first();
    // }

    // public function findAllByEmail(string $email): Collection
    // {
    //     return User::with([
    //         'perfil',
    //         'persona'
    //     ])->where('email', $email)->get();
    // }

    // public function findByEmail(string $email): ?User
    // {
    //     return User::with([
    //         'perfil',
    //         'persona'
    //     ])->where('email', $email)->first();
    // }

    // public function findAllByIdPerfil(int $idPerfil): Collection
    // {
    //     return User::with([
    //         'perfil',
    //         'persona'
    //     ])->where('id_perfil', $idPerfil)->get();
    // }

    // public function findByNameAndEmail(string $name, string $email): ?User
    // {
    //     return User::with([
    //         'perfil',
    //         'persona'
    //     ])->where('mame', $name)
    //         ->where('email', $email)
    //         ->first();
    // }

    // public function findById(int $id): ?User
    // {
    //     return User::with([
    //         'perfil',
    //         'persona'
    //     ])->find($id);
    // }

    // public function findByIdPersona(int $idPersona): ?User
    // {
    //     return User::with([
    //         'perfil',
    //         'persona'
    //     ])->where('id_persona', $idPersona)->first();
    // }

    // public function findByNameAndEmailAndPerfil(string $name, string $email, int $idPerfil): ?User
    // {
    //     return User::with([
    //         'perfil',
    //         'persona'
    //     ])->where('name', $name)
    //         ->where('email', $email)
    //         ->where('id_perfil', $idPerfil)
    //         ->first();        
    // }

    // public function create(array $data): User
    // {
    //     return User::create($data);
    // }

    // public function update(int $id, array $data): ?User
    // {
    //     $user = $this->findById($id);

    //     if ($user) {
    //         $user->update($data);
    //         return $user;
    //     }

    //     return null;
    // }

    // public function delete(int $id): bool
    // {
    //     $user = $this->findById($id);

    //     if ($user) {
    //         return $user->delete();
    //     }
        
    //     return false;
    // }
}