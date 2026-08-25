<?php

namespace App\Repositories\Eloquent;

use App\Models\Adjunto;
use App\Repositories\Contracts\IAdjuntoRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

class AdjuntoRepository implements IAdjuntoRepository
{
    public function getAll(?array $filters = null): Collection
    {
        return $this->filter($filters)->get();
    }

    public function getAllFiltered(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return $this->filter($filters)
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    public function findById(int $id): ?Adjunto
    {
        return Adjunto::with([
            'programa.tipoPrograma',
            'modulo',
            'institucion'
        ])->findOrFail($id);
    }

    public function findDuplicate(int $idPrograma, ?int $idModulo, string $titulo): ?Adjunto
    {
        $query = Adjunto::where('id_programa', $idPrograma)
            ->where('titulo', $titulo);

        if (is_null($idModulo)) {
            $query->whereNull('id_modulo');
        } else {
            $query->where('id_modulo', $idModulo);
        }

        return $query->first();
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

    private function filter(?array $filters = null): Builder
    {
        $query = Adjunto::with([
            'programa.tipoPrograma',
            'modulo',
            'institucion'
        ]);

        if (empty($filters)) {
            return $query;
        }

        // 1. Filtro por tipo de programa
        if (isset($filters['codigo_tipoprograma'])) {
            $idTipoPrograma = $filters['codigo_tipoprograma'];

            $query->whereHas('programa', function (Builder $qPrograma) use ($idTipoPrograma) {
                $qPrograma->where('codigo_tipoprograma', [$idTipoPrograma]);
            });
        }

        // 2. Filtro por estado del pago
        if (isset($filters['estado'])) {
            $query->where('estado', (bool)$filters['estado']);
        }

        // 3. Filtro por rango de fechas de pago
        if (!empty($filters['fecha_inicio'])) {
            $query->whereDate('fecha_crea', '>=', $filters['fecha_inicio']);
        }

        if (!empty($filters['fecha_final'])) {
            $query->whereDate('fecha_crea', '<=', $filters['fecha_final']);
        }

        // 4. Filtro por Nombre Completo de la Persona (Relación Pago -> Matricula -> Persona)
        if (!empty($filters['search'])) {
            $search = '%' . strtolower($filters['search']) . '%';

            $query->whereHas('programa', function (Builder $qPrograma) use ($search) {
                $qPrograma->whereRaw('LOWER(titulo) LIKE ?', [$search]);
            });
        }

        return $query;
    }
}
