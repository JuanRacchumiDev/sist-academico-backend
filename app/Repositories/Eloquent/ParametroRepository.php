<?php

namespace App\Repositories\Eloquent;

use App\Models\Parametro;
use App\Repositories\Contracts\IParametroRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ParametroRepository implements IParametroRepository
{
    /**
     * Obtiene todos los parámetros
     * @return Collection<int, Parametro>
     */
    public function getAll(): Collection
    {
        return Parametro::with('detalle')->get();
    }

    /**
     * Obtiene una lista paginada de parámetros con filtros opcionales
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator 
     */
    public function getAllFiltered(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = Parametro::with('detalle');

        if (isset($filters['nombre'])) {
            $query->where('nombre', 'like', '%' . $filters['nombre'] . '%');
        }

        if (isset($filters['descripcion'])) {
            $query->where('descripcion', 'like', '%' . $filters['descripcion'] . '%');
        }

        if (isset($filters['estado'])) {
            $query->where('estado', (bool)$filters['estado']);
        }

        return $query->paginate($perPage);
    }

    /**
     * Busca un parámetro por su clase
     * @param int $clase
     * @return Parametro|null
     */
    public function findByClase(int $clase): ?Parametro
    {
        return Parametro::with('detalle')->find($clase);
    }

    /**
     * Crea un nuevo parámetro
     * @param array<string, mixed> $data
     * @return Parametro
     */
    public function create(array $data): Parametro
    {
        return Parametro::create($data);
    }

    /**
     * Actualiza un parámetro existente
     * @param int $clase
     * @param array<string, mixed> $data
     * @return Parametro|null
     */
    public function update(int $clase, array $data): ?Parametro
    {
        $parametro = $this->findByClase($clase);
        if ($parametro) {
            $parametro->update($data);
            return $parametro;
        }
        return null;
    }

    /**
     * Elimina un parámetro por su clase
     * @param int $clase
     * @return bool
     */
    public function delete(int $clase): bool
    {
        $parametro = $this->findByClase($clase);
        if ($parametro) {
            return $parametro->delete();
        }
        return false;
    }

    /**
     * Obtiene el siguiente valor de clase
     * @return int
     */
    public function getNextParClase(): int
    {
        $maxClase = DB::table('parametro')->max('clase');
        return $maxClase ? $maxClase + 1 : 1003;
    }

    /**
     * Obtiene el detalle de un parámetro
     * @param int $clase
     * @return Parametro|null
     */
    public function getWithDetalle(int $clase): ?Parametro
    {
        return Parametro::with('detalle')->find($clase);
    }
}
