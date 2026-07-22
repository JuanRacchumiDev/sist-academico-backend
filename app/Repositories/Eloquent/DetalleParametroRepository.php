<?php

namespace App\Repositories\Eloquent;

use App\Models\DetalleParametro;
use App\Repositories\Contracts\IDetalleParametroRepository;
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
        return DetalleParametro::where('parametro_clase', $parametro_clase)->get();
    }

    /**
     * Obtiene DetalleParametros aplicando filtros dinámicos
     * @param array<string, mixed> $filters
     * @return Collection<int, DetalleParametro>
     */
    public function getAllFiltered(array $filters): Collection
    {
        $query = DetalleParametro::query();

        if (isset($filters['parametro_clase'])) {
            $clases = is_array($filters['parametro_clase']) ? $filters['parametro_clase'] : [$filters['parametro_clase']];
            $query->whereIn('parametro_clase', $clases);
        }

        if (isset($filters['en_persona'])) {
            $query->where('en_persona', (bool)$filters['en_persona']);
        }

        if (isset($filters['en_empresa'])) {
            $query->where('en_empresa', (bool)$filters['en_empresa']);
        }

        if (isset($filters['visible'])) {
            $query->where('visible', (bool)$filters['visible']);
        }

        if (isset($filters['estado'])) {
            $query->where('estado', (bool)$filters['estado']);
        }

        $query->orderBy('parametro_clase', 'asc')->orderBy('nombre', 'asc');

        return $query->get();
    }

    /**
     * Obtiene DetalleParametros aplicando filtros dinámicos
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator<DetalleParametro>
     */
    public function getAllFilteredPaginate(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = DetalleParametro::query();

        if (isset($filters['parametro_clase'])) {
            $clases = is_array($filters['parametro_clase']) ? $filters['parametro_clase'] : [$filters['parametro_clase']];
            $query->whereIn('parametro_clase', $clases);
        }

        if (isset($filters['en_persona'])) {
            $query->where('en_persona', (bool)$filters['en_persona']);
        }

        if (isset($filters['en_empresa'])) {
            $query->where('en_empresa', (bool)$filters['en_empresa']);
        }

        if (isset($filters['visible'])) {
            $query->where('visible', (bool)$filters['visible']);
        }

        if (isset($filters['estado'])) {
            $query->where('estado', (bool)$filters['estado']);
        }

        if (isset($filters['search'])) {
            $search = '%' . strtolower($filters['search']) . '%';

            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nombre) LIKE ?', [$search]);
            });
        }

        $query->orderBy('parametro_clase', 'asc')->orderBy('nombre', 'asc');

        // return $query->get();
        return $query->paginate($perPage);
    }

    /**
     * Busca un detalle de parámetro por código
     * @param int $codigo
     * @return DetalleParametro|null
     */
    public function findByCodigo(int $codigo): ?DetalleParametro
    {
        return DetalleParametro::find($codigo);
    }

    /**
     * Busca un detalle de parámetro por nombre_url
     * @param string $nombreUrl
     * @return DetalleParametro|null
     */
    public function findByNombreUrl(string $nombreUrl): ?DetalleParametro
    {
        return DetalleParametro::where('nombre_url', $nombreUrl)->first();
    }

    /**
     * Busca un detalle de parámetro por codigo y parametro_clase
     * @param int $parametro_clase
     * @param int $codigo
     * @return DetalleParametro|null
     */
    public function findByClaseAndCodigo(int $parametro_clase, int $codigo): ?DetalleParametro
    {
        return DetalleParametro::where('parametro_clase', $parametro_clase)
            ->where('codigo', $codigo)
            ->first();
    }

    /**
     * Busca un detalle de parámetro por clase y nombre_url
     * @param int $parametro_clase
     * @param string $nombreUrl
     * @return DetalleParametro|null
     */
    public function findByClaseAndNombreUrl(int $parametro_clase, string $nombreUrl): ?DetalleParametro
    {
        return DetalleParametro::where('parametro_clase', $parametro_clase)
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
}
