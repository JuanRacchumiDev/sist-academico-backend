<?php
namespace App\Repositories\Eloquent;

use App\Models\Modulo;
use App\Repositories\Contracts\IModuloRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ModuloRepository implements IModuloRepository {
    /**
     * Obtiene todos los módulos
     * @param array<string, mixed>|null $searchParams
     * @return Collection<int, Modulo>
     */
    public function getAll(?array $searchParams = null): Collection
    {
        $query = Modulo::with([
            'programa'
        ]);

        if ($searchParams) {
            $query->where(function($q) use ($searchParams) {
                if (isset($searchParams['search'])) {
                    $search = '%'.strtolower($searchParams['search']).'%';

                    $q->whereRaw('LOWER(titulo) LIKE ?', [$search]);
                }
            });
        }

        return $query->get();
    }

    /**
     * Obtiene todos los módulos con filtros aplicados.
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator<Modulo>
     */
    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = Modulo::with([
            'programa'
        ]);

        if (isset($filters['estado'])) {
            $query->where('estado', (bool)$filters['estado']);
        }

        // Aplicar búsqueda por texto
        if (isset($filters['search'])) {
            $search = '%'.strtolower($filters['search']).'%';

            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(titulo) LIKE ?', [$search]);
            });
        }

        $query->orderBy('id_programa', 'ASC')->orderBy('orden', 'ASC');

        return $query->paginate($perPage);
    }

    public function getAllByPrograma(int $id_programa): Collection
    {
        return Modulo::with([
            'programa'
        ])->where('id_programa', $id_programa)->get();
    }

    public function getNumeroOrdenByPrograma(int $id_programa): int
    {
        $ultimoOrden = Modulo::where('id_programa', $id_programa)->max('orden');
        $siguienteOrden = ($ultimoOrden === null) ? 1 : $ultimoOrden + 1;
        return $siguienteOrden;
    }

    /**
     * Obtiene un módulo por ID
     * @param int $id
     * @return Modulo|null
     */
    public function findById(int $id): ?Modulo
    {
        return Modulo::with([
            'programa'
        ])->find($id);
    }

    /**
     * Crear un módulo
     * @param array<string, mixed> $data
     * @return Modulo
     */
    public function create(array $data): Modulo
    {
        $modulo = Modulo::create($data);
        return $modulo;
    }
}