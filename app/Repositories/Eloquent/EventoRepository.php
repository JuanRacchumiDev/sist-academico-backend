<?php
namespace App\Repositories\Eloquent;

use App\Models\Evento;
use App\Repositories\Contracts\IEventoRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EventoRepository implements IEventoRepository {
    /**
     * Obtiene todos los eventos
     * @param array<string, mixed>|null $searchParams
     * @return Collection<int, Evento>
     */
    public function getAll(?array $searchParams = null): Collection
    {
        $query = Evento::with([
            'tipoEvento',
            'categoriaEvento'
        ]);

        if ($searchParams) {
            $query->where(function($q) use ($searchParams) {
                if (isset($searchParams['id_tipoevento'])) {
                    $q->where('id_tipoevento', $searchParams['id_tipoevento']);
                }

                if (isset($searchParams['id_categoriaevento'])) {
                    $q->where('id_categoriaevento', $searchParams['id_categoriaevento']);
                }

                if (isset($searchParams['fecha_inicio'])) {
                    $q->where('fecha_inicio', $searchParams['fecha_inicio']);
                }

                if (isset($searchParams['fecha_final'])) {
                    $q->where('fecha_final', $searchParams['fecha_final']);
                }

                if (isset($searchParams['search'])) {
                    $search = '%'.strtolower($searchParams['search']).'%';

                    $q->whereRaw('LOWER(titulo) LIKE ?', [$search])
                        ->orWhereRaw('LOWER(descripcion) LIKE ?', [$search]);
                }
            });
        }

        return $query->get();
    }

    /**
     * Obtiene todos los eventos con filtros aplicados.
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator<Evento>
     */
    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = Evento::with([
            'tipoEvento',
            'categoriaEvento'
        ]);

        // Aplicar filtros directos
        if (isset($filters['id_tipoevento'])) {
            $query->where('id_tipoevento', $filters['id_tipoevento']);
        }

        if (isset($filters['id_categoriaevento'])) {
            $query->where('id_categoriaevento', $filters['id_categoriaevento']);
        }

        if (isset($filters['estado'])) {
            $query->where('estado', (bool)$filters['estado']);
        }

        // Aplicar búsqueda por texto
        if (isset($filters['search'])) {
            $search = '%'.strtolower($filters['search']).'%';

            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(titulo) LIKE ?', [$search])
                    ->orWhereRaw('LOWER(descripcion) LIKE ?', [$search]);
            });
        }

        $query->orderBy('id', 'desc');

        return $query->paginate($perPage);
    }

    /**
     * Obtiene eventos por tipo de evento
     * @param int $id_tipoevento
     * @return Collection<int, Evento>
     */
    public function getAllByTipoEvento(int $id_tipoevento): Collection
    {
        return Evento::with([
            'tipoEvento',
            'categoriaEvento'
        ])->where('id_tipoevento', $id_tipoevento)->get();
    }

    /**
     * Obtiene eventos por categoría de evento
     * @param int $id_categoriaevento
     * @return Collection<int, Evento>
     */
    public function getAllByCategoriaEvento(int $id_categoriaevento): Collection
    {
        return Evento::with([
            'tipoEvento',
            'categoriaEvento'
        ])->where('id_categoriaevento', $id_categoriaevento)->get();
    }

    /**
     * Obtiene un evento por ID
     * @param int $id
     * @return Evento|null
     */
    public function findById(int $id): ?Evento
    {
        return Evento::with([
            'tipoEvento',
            'categoriaEvento'
        ])->find($id);
    }

    /**
     * Crear un evento
     * @param array<string, mixed> $data
     * @return Evento
     */
    public function create(array $data): Evento
    {
        return Evento::create($data);
    }

    /**
     * Actualiza un evento existente
     * @param int $id
     * @para, array<string, mixed> $data
     * @return Evento|null
     */
    public function update(int $id, array $data): ?Evento
    {
        $evento = $this->findById($id);

        if ($evento) {
            $evento->update($data);
            return $evento;
        }

        return null;
    }

    /**
     * Elimima un evento por su ID
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $evento = $this->findById($id);

        if ($evento) {
            return $evento->delete();
        }

        return false;
    }
}