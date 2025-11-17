<?php
namespace App\Services;

use App\DTOs\Matricula\MatriculaCreateDTO;
use App\Models\Matricula;
use App\Repositories\Contracts\IMatriculaRepository;
use App\Services\Contracts\IMatriculaService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class MatriculaService implements IMatriculaService {
    protected IMatriculaRepository $matriculaRepository;

    public function __construct(IMatriculaRepository $matriculaRepository)
    {
        $this->matriculaRepository = $matriculaRepository;
    }

    public function getAllMatriculas(?array $searchParams = null): Collection
    {
        return $this->matriculaRepository->getAll($searchParams);
    }

    public function getAllMatriculasWithFilters(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->matriculaRepository->getAllFiltered($filters, $perPage);
    }

    public function getMatriculaById(int $id): ?Matricula
    {
        return $this->matriculaRepository->findById($id);
    }

    public function createMatricula(MatriculaCreateDTO $matriculaCreateDTO): Matricula
    {
        $data = array_filter($matriculaCreateDTO->toArray(), fn($value) => !is_null($value));

        return $this->matriculaRepository->create($data);
    }

    public function deleteMatricula(int $id): bool
    {
        $matricula = $this->matriculaRepository->findById($id);

        if (!$matricula) {
            return false;
        }

        return $this->matriculaRepository->delete($id);
    }
}