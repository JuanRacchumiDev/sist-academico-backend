<?php

namespace App\Repositories\Eloquent;

use App\Models\Institucion;
use App\Repositories\Contracts\IInstitucionRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Override;
use Illuminate\Support\Facades\Log;

class InstitucionRepository implements IInstitucionRepository
{
    /**
     * Obtiene todos los registros relacionados a una institución
     * @return Collection<int, Institucion>
     */
    public function getAll(): Collection
    {
        return Institucion::with([
            'sede'
        ])->get();
    }

    public function getAllFiltered(?array $filters = null): Collection
    {
        // $query = Institucion::with([
        //     'sede'
        // ]);

        // $query = $this->applyFilters($query, $filters ?? []);

        // return $query->orderBy('nombre', 'ASC')->get();

        return $this->applyFilters($filters)->get();
    }

    public function getAllFilteredPaginate(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        // $query = Institucion::with([
        //     'sede'
        // ]);

        // $query = $this->applyFilters($query, $filters);

        // return $query->orderBy('nombre', 'ASC')->paginate($perPage);

        return $this->applyFilters($filters)->paginate($perPage);
    }

    public function findById(int $id): ?Institucion
    {
        return Institucion::findOrFail($id);
    }

    public function create(array $data): Institucion
    {
        $institucion = Institucion::create($data);
        return $institucion;
    }

    public function update(int $id, array $data): ?Institucion
    {
        $institucion = $this->findById($id);

        if (!$institucion) {
            return null;
        }

        $institucion->update($data);

        return $institucion;
    }

    /**
     * Aplica los filtros dinámicos y el ordenamiento a la consulta de Institucion
     * @param array<string, mixed> $filters
     * @return Builder<Institucion>
     */
    private function applyFilters(array $filters): Builder
    {
        Log::info('Validando filters in applyFilters', ['filters' => $filters]);

        $query = Institucion::with([
            'sede'
        ]);

        if (isset($filters['codigo_sede'])) {
            $query->where('codigo_sede', $filters['codigo_sede']);
        }

        if (isset($filters['nombre'])) {
            $nombre = "%" . strtolower($filters['nombre']) . "%";
            $query->where(function ($q) use ($nombre) {
                $q->whereRaw('LOWER(nombre) LIKE ?', [$nombre]);
            });
        }

        if (isset($filters['is_cliente']) && $filters['is_cliente'] !== '') {
            $query->where('is_cliente', (bool) $filters['is_cliente']);
        }

        if (isset($filters['estado']) && $filters['estado'] !== '') {
            $query->where('estado', (bool) $filters['estado']);
        }

        return $query;
    }
}
