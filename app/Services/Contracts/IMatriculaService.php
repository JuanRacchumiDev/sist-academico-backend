<?php
namespace App\Services\Contracts;

use App\DTOs\Matricula\MatriculaCreateDTO;
use App\Models\Matricula;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IMatriculaService {
    public function getAllMatriculas(?array $searchParams = null): Collection;

    public function getAllMatriculasWithFilters(array $filters, int $perPage): LengthAwarePaginator;

    public function getMatriculaById(int $id): ?Matricula;

    public function createMatricula(MatriculaCreateDTO $matriculaCreateDTO): Matricula;

    public function deleteMatricula(int $id): bool;
}