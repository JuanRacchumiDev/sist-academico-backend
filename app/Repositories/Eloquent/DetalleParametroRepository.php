<?php

namespace App\Repositories\Eloquent;

use App\Models\DetalleParametro;
use App\Repositories\Contracts\IDetalleParametroRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DetalleParametroRepository implements IDetalleParametroRepository
{
    /**
     * Obtiene todos los registros relacionados a un parámetro
     * @return Collection<int, DetalleParametro>
     */
    public function getAllByClase(int $parametro_clase): Collection
    {
        return DetalleParametro::with([
            'programasPorTipo',
            'programasPorCategoria',
            'programasPorSegmento',
            'personas'
        ])
            ->where('parametro_clase', $parametro_clase)
            ->get();
    }

    /**
     * Obtiene DetalleParametros aplicando filtros dinámicos
     * @param array<string, mixed> $filters
     * @return Collection<int, DetalleParametro>
     */
    public function getAllFiltered(array $filters): Collection
    {
        return $this->applyFilters($filters)->get();
    }

    /**
     * Obtiene DetalleParametros aplicando filtros dinámicos paginados
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator<DetalleParametro>
     */
    public function getAllFilteredPaginate(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return $this->applyFilters($filters)->paginate($perPage);
    }

    /**
     * Busca un detalle de parámetro por código
     * @param int $codigo
     * @return DetalleParametro|null
     */
    public function findByCodigo(int $codigo): ?DetalleParametro
    {
        return DetalleParametro::with([
            'programasPorTipo',
            'programasPorCategoria',
            'programasPorSegmento',
            'personas'
        ])->where('codigo', $codigo)->first();
    }

    /**
     * Busca un detalle de parámetro por nombre_url
     * @param string $nombreUrl
     * @return DetalleParametro|null
     */
    public function findByNombreUrl(int $parametroClase, string $nombreUrl): ?DetalleParametro
    {
        return DetalleParametro::with(
            [
                'programasPorTipo',
                'programasPorCategoria',
                'programasPorSegmento',
                'personas'
            ]
        )->where('parametro_clase', $parametroClase)
            ->where('nombre_url', $nombreUrl)
            ->first();
    }

    /**
     * Crea un nuevo detalle parámetro
     * @param array<string, mixed> $data
     * @return DetalleParametro
     */
    public function create(array $data): DetalleParametro
    {
        return DetalleParametro::create($data);
    }

    /**
     * Actualiza un detalle de parámetro
     * @param int $codigo
     * @param array<string, mixed> $data
     * @return DetalleParametro|null
     */
    public function update(int $codigo, array $data): ?DetalleParametro
    {
        $detalle = $this->findByCodigo($codigo);

        if ($detalle) {
            $detalle->update($data);
            return $detalle;
        }

        return null;
    }

    /**
     * Elimina un parámetro por su codigo
     * @param int $codigo
     * @return bool
     */
    public function delete(int $codigo): bool
    {
        $detalle = $this->findByCodigo($codigo);

        if ($detalle) {
            return $detalle->delete();
        }

        return false;
    }

    /**
     * Aplica los filtros dinámicos y el ordenamiento a la consulta de DetalleParametro
     * @param array<string, mixed> $filters
     * @return Builder<DetalleParametro>
     */
    private function applyFilters(array $filters): Builder
    {
        // $query = DetalleParametro::query();
        $query = DetalleParametro::with([
            'programasPorTipo',
            'programasPorCategoria',
            'programasPorSegmento',
            'personas'
        ]);

        // Filtro por parametro_clase (admite un valor individual o un array de valores)
        if (isset($filters['parametro_clase']) && $filters['parametro_clase'] !== '') {
            $clases = is_array($filters['parametro_clase']) ? $filters['parametro_clase'] : [$filters['parametro_clase']];
            $query->whereIn('parametro_clase', $clases);
        }

        // Filtro booleano: en_persona
        if (isset($filters['en_persona']) && $filters['en_persona'] !== '') {
            $query->where('en_persona', (bool) $filters['en_persona']);
        }

        // Filtro booleano: en_empresa
        if (isset($filters['en_empresa']) && $filters['en_empresa'] !== '') {
            $query->where('en_empresa', (bool) $filters['en_empresa']);
        }

        // Filtro booleano: visible
        if (isset($filters['visible']) && $filters['visible'] !== '') {
            $query->where('visible', (bool) $filters['visible']);
        }

        // Filtro booleano: estado
        if (isset($filters['estado']) && $filters['estado'] !== '') {
            $query->where('estado', (bool) $filters['estado']);
        }

        // Búsqueda textual por nombre
        if (!empty($filters['search'])) {
            $search = '%' . strtolower($filters['search']) . '%';
            $query->where(function (Builder $q) use ($search) {
                $q->whereRaw('LOWER(nombre) LIKE ?', [$search]);
            });
        }

        // Ordenamiento por defecto
        return $query->orderBy('parametro_clase', 'asc')
            ->orderBy('nombre', 'asc');
    }
}
