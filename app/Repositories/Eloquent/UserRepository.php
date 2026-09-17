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
    public function getAll(array $filters = []): Collection
    {
        $query = User::with(['perfil', 'persona.grupos']);
        $query = $this->applyFilters($query, $filters);
        return $query->get();
    }

    public function getAllFiltered(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = User::with([
            'perfil',
            'persona.grupos'
        ]);

        $query = $this->applyFilters($query, $filters);

        return $query->orderBy('name', 'ASC')->paginate($perPage);
    }

    public function findOne(array $filters): ?User
    {
        return $this->applyFilters(User::with(['perfil', 'persona.grupos']), $filters)->first();
    }

    public function findById(int $id): ?User
    {
        return User::with([
            'perfil',
            'persona.grupos'
        ])->findOrFail($id);
    }

    public function create(array $data): User
    {
        $user = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'codigo_perfil' => $data['codigo_perfil'],
            'user_crea' => $data['user_crea'],
            'password'  => Hash::make($data['password']),
            'id_persona' => $data['id_persona'] ?? null
        ]);

        return $user;
    }

    public function update(int $id, array $data): ?User
    {
        $user = User::find($id);

        if ($user) {
            if (!empty($data['password'])) {
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
        if (!empty($filters['id'])) {
            $query->where('id', $filters['id']);
        }

        if (!empty($filters['codigo_perfil'])) {
            $query->where('codigo_perfil', $filters['codigo_perfil']);
        }

        if (!empty($filters['id_persona'])) {
            $query->where('id_persona', $filters['id_persona']);
        }

        if (isset($filters['estado']) && $filters['estado'] !== '') {
            $query->where('estado', filter_var($filters['estado'], FILTER_VALIDATE_BOOLEAN));
        }

        if (!empty($filters['name'])) {
            $search = '%' . strtolower($filters['name']) . '%';
            $query->whereRaw('LOWER(name) LIKE ?', [$search]);
        }

        if (!empty($filters['email'])) {
            $search = '%' . strtolower($filters['email']) . '%';
            $query->whereRaw('LOWER(email) LIKE ?', [$search]);
        }

        if (!empty($filters['perfil_nombre'])) {
            $search = '%' . strtolower($filters['perfil_nombre']) . '%';
            $query->whereHas('perfil', function (Builder $q) use ($search) {
                $q->whereRaw('LOWER(nombre) LIKE ?', [$search]);
            });
        }

        if (!empty($filters['persona_search'])) {
            $search = '%' . strtolower($filters['persona_search']) . '%';
            $query->whereHas('persona', function (Builder $q) use ($search) {
                $q->where(function (Builder $subQ) use ($search) {
                    $subQ->whereRaw('LOWER(nombre_completo) LIKE ?', [$search])
                        ->orWhere('numero_documento', 'LIKE', $search);
                });
            });
        }

        if (!empty($filters['numero_documento'])) {
            $query->whereHas('persona', function (Builder $q) use ($filters) {
                $q->where('numero_documento', $filters['numero_documento']);
            });
        }

        if (!empty($filters['id_grupo_persona']) || !empty($filters['codigo_grupo'])) {
            $codigoGrupo = $filters['id_grupo_persona'] ?? $filters['codigo_grupo'];

            $query->whereHas('persona.grupos', function (Builder $q) use ($codigoGrupo) {
                $q->where('academic.grupo_persona.codigo_grupo', $codigoGrupo);
            });
        }

        return $query;
    }
}
