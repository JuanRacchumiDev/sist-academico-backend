<?php

namespace App\Repositories\Eloquent;

use App\Models\Plantilla;
use App\Repositories\Contracts\IPlantillaRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Override;

class PlantillaRepository implements IPlantillaRepository
{
    public function getAll(?array $searchParams = null): Collection
    {
        $query = Plantilla::with([
            'institucion'
        ]);

        $query = $this->applyFilters($query, $searchParams ?? []);

        return $query->orderBy('fecha_crea', 'DESC')->get();
    }

    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = Plantilla::with([
            'institucion',
            'tipoPrograma'
        ]);

        $query = $this->applyFilters($query, $filters);

        return $query->orderBy('fecha_crea', 'DESC')->paginate($perPage);
    }

    public function findById(int $id): ?Plantilla
    {
        return Plantilla::with([
            'institucion'
        ])->findOrFail($id);
    }

    public function findDuplicate(int $idInstitucion, int $idSucursal, int $codigoTipoPrograma, string $tipoDisenio, string $disenioDefault): ?Plantilla
    {
        $query = Plantilla::where('id_institucion', $idInstitucion)
            ->where('id_sucursal', $idSucursal)
            ->where('codigo_tipoprograma', $codigoTipoPrograma)
            ->where('tipoDisenio', $tipoDisenio)
            ->where('disenio_default', $disenioDefault);

        return $query->first();
    }


    private function applyFilters(Builder $query, array $filters): Builder
    {
        if (!empty($filters['id_institucion'])) {
            $query->where('id_institucion', $filters['id_institucion']);
        }

        if (!empty($filters['codigo_tipoprograma'])) {
            $query->where('codigo_tipoprograma', $filters['codigo_tipoprograma']);
        }

        if (!empty($filters['id_sucursal'])) {
            $query->where('id_sucursal', $filters['id_sucursal']);
        }

        if (!empty($filters['search'])) {
            $search = '%' . strtolower($filters['search']) . '%';
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nombre) LIKE ?', [$search]);
            });
        }

        return $query;
    }
}
