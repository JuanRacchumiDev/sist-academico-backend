<?php

namespace App\Repositories\Eloquent;

use App\Models\Plantilla;
use App\Repositories\Contracts\IPlantillaRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Override;

class PlantillaRepository implements IPlantillaRepository
{
    public function getAll(?array $searchParams = null): Collection
    {
        $query = Plantilla::with([
            'institucion'
        ]);

        $query = $this->applyFilters($query, $searchParams ?? []);

        return $query->orderBy('fecha_crea', 'DESC')->get();
    }

    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = Plantilla::with([
            'institucion'
        ]);

        $query = $this->applyFilters($query, $filters);

        return $query->orderBy('fecha_crea', 'DESC')->paginate($perPage);
    }

    public function findById(int $id): ?Plantilla
    {
        return Plantilla::with([
            'institucion'
        ])->findOrFail($id);
    }

    public function create(array $data): Plantilla
    {
        $plantilla = Plantilla::create($data);
        return $plantilla;
    }

    public function update(int $id, array $data): ?Plantilla
    {
        $plantilla = $this->findById($id);

        if (!$plantilla) {
            return null;
        }

        $plantilla->update($data);

        return $plantilla;
    }

    public function delete(int $id): bool
    {
        $plantilla = $this->findById($id);

        return $plantilla ? (bool)$plantilla->delete() : false;
    }

    private function applyFilters(Builder $query, array $filters): Builder
    {
        if (isset($filters['id_institucion'])) {
            $query->where('id_institucion', $filters['id_institucion']);
        }

        if (isset($filters['search'])) {
            $search = '%' . strtolower($filters['search']) . '%';
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nombre) LIKE ?', [$search]);
            });
        }

        return $query;
    }
}
