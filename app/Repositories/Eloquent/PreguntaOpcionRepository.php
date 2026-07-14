<?php

namespace App\Repositories\Eloquent;

use App\Models\PreguntaOpcion;
use App\Repositories\Contracts\IPreguntaOpcionRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Override;

class PreguntaOpcionRepository implements IPreguntaOpcionRepository
{
    public function getAll(?array $searchParams = null): Collection
    {
        $query = PreguntaOpcion::with([
            'pregunta',
            'respuestas'
        ]);

        $query = $this->applyFilters($query, $searchParams ?? []);

        return $query->orderBy('id', 'DESC')->get();
    }

    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = PreguntaOpcion::with([
            'pregunta',
            'respuestas'
        ]);

        $query = $this->applyFilters($query, $filters);

        return $query->orderBy('id', 'DESC')->paginate($perPage);
    }

    public function findById(int $id): ?PreguntaOpcion
    {
        return PreguntaOpcion::with([
            'pregunta',
            'respuestas'
        ])->findOrFail($id);
    }

    public function create(array $data): PreguntaOpcion
    {
        $preguntaOpcion = PreguntaOpcion::create($data);
        return $preguntaOpcion;
    }

    public function update(int $id, array $data): ?PreguntaOpcion
    {
        $preguntaOpcion = $this->findById($id);

        if (!$preguntaOpcion) {
            return null;
        }

        $preguntaOpcion->update($data);

        return $preguntaOpcion;
    }

    public function delete(int $id): bool
    {
        $preguntaOpcion = $this->findById($id);

        return $preguntaOpcion ? $preguntaOpcion->delete() : false;
    }

    private function applyFilters(Builder $query, array $filters): Builder
    {
        if (isset($filters['id_pregunta'])) {
            $query->where('id_pregunta', $filters['id_pregunta']);
        }

        if (isset($filters['texto_opcion'])) {
            $search = '%' . strtolower($filters['texto_opcion']) . '%';
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(texto_opcion) LIKE ?', [$search]);
            });
        }

        return $query;
    }
}
