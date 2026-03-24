<?php
namespace App\Repositories\Eloquent;

use App\Models\Adjunto;
use App\DTOs\Adjunto\AdjuntoCreateDTO;
use App\Repositories\Contracts\IAdjuntoRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class AdjuntoRepository implements IAdjuntoRepository {
    public function getAll(?array $searchParams = null): Collection
    {
        $query = Adjunto::with([
            'programa',
            'modulo',
            'institucion'
        ]);

        $this->applyFilters($query, $searchParams ?? []);

        return $query->get();
    }

    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = Adjunto::with([
            'programa',
            'modulo',
            'institucion'
        ]);

        $this->applyFilters($query, $filters);

        $query->orderBy('id', 'DESC');

        return $query->paginate($perPage);
    }

    public function findById(int $id): ?Adjunto
    {
        return Adjunto::with([
            'programa',
            'modulo',
            'institucion'
        ])->findOrFail($id);
    }

    public function create(array $data): Adjunto
    {
        $adjunto = Adjunto::create($data);

        return $adjunto;
    }

    public function update(int $id, array $data): ?Adjunto
    {
        $adjunto = $this->findById($id);

        if ($adjunto) {
            $adjunto->update($data);
            return $adjunto;
        }

        return null;
    }

    public function delete(int $id): bool
    {
        $adjunto = $this->findById($id);

        if ($adjunto) {
            return $adjunto->delete();
        }

        return false;
    }

    private function applyFilters($query, array $filters): void
    {
        if (isset($filters['id_programa'])) {
            $query->where('id_programa', $filters['id_programa']);
        }

        if (isset($filters['id_modulo'])) {
            $query->where('id_modulo', $filters['id_modulo']);
        }

        if (isset($filters['id_institucion'])) {
            $query->where('id_institucion', $filters['id_institucion']);
        }

        if (isset($filters['search'])) {
            $search = '%' . strtolower($filters['search']) . '%';
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(titulo) LIKE ?', [$search])
                  ->orWhereRaw('LOWER(descripcion) LIKE ?', [$search]);
            });
        }
    }
}