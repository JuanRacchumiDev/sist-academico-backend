<?php
namespace App\Repositories\Eloquent;

use App\DTOs\Matricula\MatriculaCreateDTO;
use App\Models\Matricula;
use App\Models\DetalleMatricula;
use App\Models\PersonaPrograma;
use App\Models\Programa;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\IMatriculaRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
use Throwable;

class MatriculaRepository implements IMatriculaRepository {
    public function getAll(?array $searchParams = null): Collection
    {
        $query = Matricula::with([
            'alumno',
            'sede',
            'detalles',
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
            'detalles',
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
        // throw ValidationException::withMessages(['filters' => $filters]);

        return Matricula::with([
            'alumno',
            'sede',
            'detalles',
            'estadoMatricula'
        ])->find($filters['id_matricula']);
    }

    public function findById(int $id): ?Matricula
    {
        return Matricula::with([
            'alumno',
            'sede',
            'detalles',
            'estadoMatricula'
        ])->find($id);
    }

    /**
     * @throws Throwable
     */
    public function create(MatriculaCreateDTO $dto): Matricula
    {
        return DB::transaction(function () use ($dto){
            $matriculaData = $dto->except('programas')->toArray();
            
            $matriculaData = array_filter($matriculaData, fn($value) => !is_null($value));
            
            $matricula = Matricula::create($matriculaData);
            
            $detalleMatriculaData = [];
            $personaProgramaData = [];

            $programas = Programa::whereIn('id', $dto->programas)
                ->pluck('nombre', 'id');

            foreach ($dto->programas as $programaId) {
                $nombrePrograma = $programas->get($programaId) ?? "Nombre no encontrado";

                $detalleMatriculaData[] = [
                    'id_matricula' => $matricula->id,
                    'id_programa' => $programaId,
                    'nombre_programa' => $nombrePrograma,
                    'estado' => $dto->estado
                ];

                $personaProgramaData[] = [
                    'id_persona' => $dto->id_alumno,
                    'id_programa' => $programaId
                ];
            }

            if (!empty($detalleMatriculaData)) {
                DetalleMatricula::insert($detalleMatriculaData);
            }

            if (!empty($personaProgramaData)) {
                PersonaPrograma::insert($personaProgramaData);
            }

            return $matricula;
        });
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