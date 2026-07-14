<?php

namespace App\Repositories\Eloquent;

use App\Models\Programa;
use App\Repositories\Contracts\IProgramaRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

class ProgramaRepository implements IProgramaRepository
{
    /**
     * Obtiene todos los programas
     * @param array<string, mixed>|null $searchParams
     * @return Collection<int, Programa>
     */
    public function getAll(?array $searchParams = null): Collection
    {
        $query = Programa::with([
            'segmento',
            'tipoPrograma',
            'categoriaPrograma',
            'detalleModulos'
        ]);

        $query = $this->applyFilters($query, $searchParams ?? []);

        return $query->orderBy('fecha_inicio', 'DESC')->get();
    }

    /**
     * Obtiene todos los programas con filtros aplicados.
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator<Programa>
     */
    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = Programa::with([
            'segmento',
            'tipoPrograma',
            'categoriaPrograma',
            'detalleModulos'
        ]);

        $query = $this->applyFilters($query, $filters);

        return $query->orderBy('fecha_inicio', 'DESC')->paginate($perPage);
    }

    /**
     * Obtiene un programa por ID
     * @param int $id
     * @return Programa|null
     */
    public function findById(int $id): ?Programa
    {
        return Programa::with([
            'segmento',
            'tipoPrograma',
            'categoriaPrograma',
            'detalleModulos'
        ])->findOrFail($id);
    }

    /**
     * Crear un programa
     * @param array<string, mixed> $data
     * @return Programa
     */
    public function create(array $data): Programa
    {
        $programa = Programa::create($data);
        return $programa;
    }

    /**
     * Actualiza un programa existente
     * @param int $id
     * @param array<string, mixed> $data
     * @return Programa|null
     */
    public function update(int $id, array $data): ?Programa
    {
        $programa = $this->findById($id);

        if (!$programa) {
            return null;
        }

        $programa->update($data);

        return $programa;
    }

    /**
     * Elimima un programa por su ID
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $programa = $this->findById($id);

        return $programa ? $programa->delete() : false;
    }

    private function applyFilters(Builder $query, array $filters): Builder
    {
        if (isset($filters['id_segmento'])) {
            $query->where('id_segmento', $filters['id_segmento']);
        }

        if (isset($filters['id_tipoprograma'])) {
            $query->where('id_tipoprograma', $filters['id_tipoprograma']);
        }

        if (isset($filters['id_categoriaprograma'])) {
            $query->where('id_categoriaprograma', $filters['id_categoriaprograma']);
        }

        if (isset($filters['id_institucion'])) {
            $query->where('id_institucion', $filters['id_institucion']);
        }

        if (isset($filters['titulo'])) {
            $search = '%' . strtolower($filters['titulo']) . '%';
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(titulo) LIKE ?', [$search]);
            });
        }

        if (isset($filters['modalidad'])) {
            $query->where('modalidad', $filters['modalidad']);
        }

        return $query;
    }
}
