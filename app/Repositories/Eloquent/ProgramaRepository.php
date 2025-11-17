<?php
namespace App\Repositories\Eloquent;

use App\Models\Programa;
use App\Repositories\Contracts\IProgramaRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProgramaRepository implements IProgramaRepository {
    /**
     * Obtiene todos los programas
     * @param array<string, mixed>|null $searchParams
     * @return Collection<int, Programa>
     */
    public function getAll(?array $searchParams = null): Collection
    {
        $query = Programa::query();

        if ($searchParams) {
            $query->where(function($q) use ($searchParams) {
                if (isset($searchParams['search'])) {
                    $search = '%'.strtolower($searchParams['search']).'%';

                    $q->whereRaw('LOWER(nombre) LIKE ?', [$search])
                        ->orWhereRaw('LOWER(sigla) LIKE ?', [$search]);
                }
            });
        }

        return $query->get();
    }

    /**
     * Obtiene todos los programas con filtros aplicados.
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator<Programa>
     */
    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = Programa::query();

        if (isset($filters['estado'])) {
            $query->where('estado', (bool)$filters['estado']);
        }

        // Aplicar búsqueda por texto
        if (isset($filters['search'])) {
            $search = '%'.strtolower($filters['search']).'%';

            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nombre) LIKE ?', [$search])
                    ->orWhereRaw('LOWER(sigla) LIKE ?', [$search]);
            });
        }

        $query->orderBy('id', 'desc');

        return $query->paginate($perPage);
    }

    /**
     * Obtiene un programa por ID
     * @param int $id
     * @return Programa|null
     */
    public function findById(int $id): ?Programa
    {
        return Programa::find($id);
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

        if ($programa) {
            $programa->update($data);
            return $programa;
        }

        return null;
    }

    /**
     * Elimima un programa por su ID
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $programa = $this->findById($id);

        if ($programa) {
            return $programa->delete();
        }

        return false;
    }
}