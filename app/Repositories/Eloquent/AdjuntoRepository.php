<?php

namespace App\Repositories\Eloquent;

use App\Models\Adjunto;
use App\Repositories\Contracts\IAdjuntoRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

class AdjuntoRepository implements IAdjuntoRepository
{
    public function getAll(?array $searchParams = null): Collection
    {
        $query = $this->applyFilters($searchParams ?? []);

        $adjuntos = $query->get();

        return $adjuntos;
    }

    public function getAllFiltered(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return $this->applyFilters($filters)->paginate($perPage);
    }

    public function findById(int $id): ?Adjunto
    {
        return Adjunto::with([
            'programa.tipoPrograma',
            'modulo',
            'institucion'
        ])->find($id);
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

    private function applyFilters(array $filters): Builder
    {
        $query = Adjunto::query()
            ->with([
                'programa.tipoPrograma',
                'modulo',
                'institucion'
            ]);

        // Filtro por búsqueda
        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function (Builder $q) use ($search) {
                // Convertimos el término de búsqueda a minúsculas
                $searchTerm = '%' . mb_strtolower($search, 'UTF-8') . '%';

                // Búsqueda insensible a mayúsculas/minúsculas usando LOWER()
                $q->whereRaw('LOWER(titulo) LIKE ?', [$searchTerm]);
            });
        }

        // Filtro por programa
        if (!empty($filters['id_programa'])) {
            $query->where('codigo_tipocertificado', $filters['codigo_tipocertificado']);
        }

        // 2. Filtro por modulo
        if (isset($filters['id_modulo'])) {
            $query->where('id_modulo', $filters['id_modulo']);
        }

        // 3. Filtro por rango de fechas de pago
        if (!empty($filters['fecha_inicio'])) {
            $query->whereDate('fecha_crea', '>=', $filters['fecha_inicio']);
        }

        if (!empty($filters['fecha_final'])) {
            $query->whereDate('fecha_crea', '<=', $filters['fecha_final']);
        }

        return $query->orderBy('id', 'DESC');
    }
}
