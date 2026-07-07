<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\IUserRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class UserRepository implements IUserRepository
{

    public function getAll(array $filters = [], ?int $perPage = null)
    {
        $query = $this->applyFilters(User::query(), $filters);
        return $perPage ? $query->paginate($perPage) : $query->get();
    }

    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = User::with([
            'perfil',
            'persona'
        ]);

        $this->applyFilters($query, $filters);

        return $query->orderBy('name', 'ASC')->paginate($perPage);
    }

    public function findOne(array $filters): ?User
    {
        return $this->applyFilters(User::query(), $filters)->first();
    }

    public function findById(int $id): ?User
    {
        return User::with([
            'perfil',
            'persona'
        ])->findOrFail($id);
    }

    public function create(array $data): User
    {
        // $user = User::create($data);
        $user = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'id_perfil' => $data['id_perfil'],
            'password'  => Hash::make($data['password']),
            'id_persona' => $data['id_persona'] ?? null
        ]);

        return $user;
    }

    public function update(int $id, array $data): ?User
    {
        $user = User::find($id);

        if ($user) {
            // Si el password viene en la data, se encripta antes de guardar
            if (isset($data['password']) && !empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $user->update($data);
            return $user;
        }

        return null;
    }

    public function delete(int $id): bool
    {
        $user = User::find($id);

        if ($user) {
            return (bool) $user->delete();
        }

        return false;
    }

    private function applyFilters(Builder $query, array $filters): Builder
    {
        if (isset($filters['id'])) {
            $query->where('id', $filters['id']);
        }

        if (isset($filters['id_perfil'])) {
            $query->where('id_perfil', $filters['id_perfil']);
        }

        if (isset($filters['id_persona'])) {
            $query->where('id_persona', $filters['id_persona']);
        }

        if (isset($filters['name']) && !empty($filters['name'])) {
            $search = '%' . strtolower($filters['name']) . '%';
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', [$search]);
            });
        }

        if (isset($filters['email']) && !empty($filters['email'])) {
            $search = '%' . strtolower($filters['email']) . '%';
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(email) LIKE ?', [$search]);
            });
        }

        return $query;
    }
}
