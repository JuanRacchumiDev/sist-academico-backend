<?php
namespace App\Services\Contracts;

use App\DTOs\Matricula\MatriculaCreateDTO;
use App\DTOs\Matricula\MatriculaUpdateDTO;
use App\Models\Matricula;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IMatriculaService {
    /**
     * Obtener todas las matrículas
     * @param array<string, mixed>|null $searchParams
     * @return Collection<int, Matricula>
     */
    public function getAllMatriculas(?array $searchParams = null): Collection;
    
    /**
     * Obtiene todas las matrículas con filtros aplicados
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator<Matricula>
     */
    public function getAllMatriculasWithFilters(array $filters, int $perPage): LengthAwarePaginator;
    
    /**
     * Obtiene una matrícula por ID
     * @param int $id
     * @return Matricula|null
     */
    public function getMatriculaById(int $id): ?Matricula;

    public function generateFichaPDF(int $id);

    public function deleteFichaPDF(int $id): bool;
    
    /**
     * Crear una nueva matrícula
     * @param MatriculaCreateDTO $matriculaCreateDTO
     * @return Matricula
     */
    public function createMatricula(MatriculaCreateDTO $dto): Matricula;

    public function updateMatricula(int $id, MatriculaUpdateDTO $dto): ?Matricula;
}