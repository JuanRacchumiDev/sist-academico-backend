<?php
namespace App\Repositories\Eloquent;

use App\Models\{Matricula, DetalleMatricula, Pago, Programa};
use App\Repositories\Contracts\{IMatriculaRepository, IPersonaRepository, IDetalleParametroRepository};
use App\DTOs\Matricula\MatriculaCreateDTO;
use Illuminate\Support\Facades\{DB, Log, Storage};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class MatriculaRepository implements IMatriculaRepository {
    public function getAll(?array $searchParams = null): Collection
    {
        $query = Matricula::with([
            'persona',
            'estadoMatricula',
            'detalles'
        ]);

        $this->applyFilters($query, $searchParams ?? []);

        return $query->get();
    }

    /**
     * Obtiene todas las matrículas con filtros aplicados.
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator<Matricula>
     */
    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = Matricula::with([
            'persona',
            'estadoMatricula',
            'detalles'
        ]);

        $this->applyFilters($query, $filters);

        $query->orderBy('fecha_matricula', 'DESC');
        
        return $query->paginate($perPage);
    }

    /**
     * Busca una matrícula por su ID
     * @param int $id
     * @return Matricula|null
     */
    public function findById(int $id): ?Matricula
    {
        return Matricula::with([
            'persona',
            'estadoMatricula',
            'detalles.programa.tipoPrograma',
            'detalles.programa.categoriaPrograma'
        ])->findOrFail($id);
    }

    /**
     * Crear una matrícula
     * @param array<string, mixed> $data
     * @return Matricula
     */
    public function create(array $data): Matricula
    {
        // Crear el registro de matrícula
        $matricula = Matricula::create($data);

        return $matricula;
    }

    /**
     * Actualiza una matrícula existente
     * @param int $id
     * @param array<string, mixed> $data
     * @return Matricula|null
     */
    public function update(int $id, array $data): ?Matricula
    {
        $matricula = $this->findById($id);

        if ($matricula) {
            $matricula->update($data);
            return $matricula;
        }

        return null;
    }

    /**
     * Elimima una matrícula por su ID
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $matricula = $this->findById($id);

        if ($matricula) {
            return $matricula->delete();
        }

        return false;
    }

    private function applyFilters($query, array $filters): void
    {
        if (isset($filters['id_persona'])) {
            $query->where('id_persona', $filters['id_persona']);
        }

        if (isset($filters['id_estadomatricula'])) {
            $query->where('id_estadomatricula', $filters['id_estadomatricula']);
        }

        if (isset($filters['fecha_matricula'])) {
            $query->where('fecha_matricula', '>=', $filters['fecha_matricula']);
        }

        if (isset($filters['estado'])) {
            $query->where('estado', filter_var($filters['estado'], FILTER_VALIDATE_BOOLEAN));
        }
    }
}