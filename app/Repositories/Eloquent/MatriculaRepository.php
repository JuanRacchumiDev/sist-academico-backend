<?php
namespace App\Repositories\Eloquent;

use App\Models\Matricula;
use App\Repositories\Contracts\IMatriculaRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class MatriculaRepository implements IMatriculaRepository {
    public function getAll(?array $searchParams = null): Collection
    {
        $query = Matricula::with([
            'alumno',
            'sede',
            'programa',
            'estadoMatricula'
        ]);

        if ($searchParams) {
            $query->where(function($q) use ($searchParams) {
                if (isset($searchParams['search'])) {
                    $search = '%'.strtolower($searchParams['search']).'%';

                    $q->whereRaw('LOWER(nombre_alumno) LIKE ?', [$search])
                        ->orWhereRaw('LOWER(nombre_sede) LIKE ?', [$search])
                        ->orWhereRaw('LOWER(nombre_programa) LIKE ?', [$search]);
                }
            });
        }

        return $query->get();
    }

    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = Matricula::with([
            'alumno',
            'sede',
            'programa',
            'estadoMatricula'
        ]);

        if (isset($filters['estado'])) {
            $query->where('estado', (bool)$filters['estado']);
        }

        // Aplicar búsqueda por texto
        if (isset($filters['search'])) {
            $search = '%'.strtolower($filters['search']).'%';

            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nombre_alumno) LIKE ?', [$search])
                    ->orWhereRaw('LOWER(nombre_sede) LIKE ?', [$search])
                    ->orWhereRaw('LOWER(nombre_programa) LIKE ?', [$search]);
            });
        }

        $query->orderBy('id', 'desc');

        return $query->paginate($perPage);
    }

    public function getUniqueForFilters(array $filters): ?Matricula
    {
        $query = Matricula::with([
            'alumno',
            'sede',
            'programa',
            'estadoMatricula'
        ]);

        if (isset($filters['id_alumno'])) {
            $query->where('id_alumno', (int)$filters['id_alumno']);
        }

        if (isset($filters['id_programa'])) {
            $query->where('id_programa', (int)$filters['id_programa']);
        }

        return $query->first();
    }

    public function findById(int $id): ?Matricula
    {
        return Matricula::with([
            'alumno',
            'sede',
            'programa',
            'estadoMatricula'
        ])->find($id);
    }

    public function create(array $data): Matricula
    {
        $matricula = Matricula::create($data);
        return $matricula;
    }

    public function update(int $id, array $data): ?Matricula
    {
        $matricula = $this->findById($id);

        if ($matricula) {
            $matricula->update($data);
            return $matricula;
        }

        return null;
    }

    public function delete(int $id): bool
    {
        $matricula = $this->findById($id);

        if ($matricula) {
            return $matricula->delete();
        }

        return false;
    }
}