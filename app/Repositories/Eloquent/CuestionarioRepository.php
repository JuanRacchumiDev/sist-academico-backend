<?php

namespace App\Repositories\Eloquent;

use App\Models\Cuestionario;
use App\Repositories\Contracts\ICuestionarioRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Override;

class CuestionarioRepository implements ICuestionarioRepository
{
    public function getAll(?array $searchParams = null): Collection
    {
        $query = Cuestionario::with([
            'programa',
            'modulo',
            'preguntas'
        ]);

        $query = $this->applyFilters($query, $searchParams ?? []);

        return $query->orderBy('id', 'DESC')->get();
    }

    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = Cuestionario::with([
            'programa',
            'modulo',
            'preguntas'
        ]);

        $query = $this->applyFilters($query, $filters);

        return $query->orderBy('id', 'DESC')->paginate($perPage);
    }

    public function findById(int $id): ?Cuestionario
    {
        return Cuestionario::with([
            'programa',
            'modulo',
            'preguntas'
        ])->findOrFail($id);
    }

    public function create(array $data): Cuestionario
    {
        $cuestionario = Cuestionario::create($data);
        return $cuestionario;
    }

    public function update(int $id, array $data): ?Cuestionario
    {
        $cuestionario = $this->findById($id);

        if (!$cuestionario) {
            return null;
        }

        $cuestionario->update($data);

        return $cuestionario;
    }

    public function delete(int $id): bool
    {
        $cuestionario = $this->findById($id);

        return $cuestionario ? $cuestionario->delete() : false;
    }

    private function applyFilters(Builder $query, array $filters): Builder
    {
        if (isset($filters['id_programa'])) {
            $query->where('id_programa', $filters['id_programa']);
        }

        if (isset($filters['id_modulo'])) {
            $query->where('id_modulo', $filters['id_modulo']);
        }

        if (isset($filters['titulo'])) {
            $search = '%' . strtolower($filters['titulo']) . '%';
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(titulo) LIKE ?', [$search]);
            });
        }

        return $query;
    }
}
