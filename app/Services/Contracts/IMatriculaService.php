<?php

namespace App\Services\Contracts;

use App\DTOs\Matricula\MatriculaCreateDTO;
use App\DTOs\Matricula\MatriculaUpdateDTO;
use App\Models\Matricula;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IMatriculaService
{
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

    /**
     * Verifica si una persona ya cuenta con una matrícula en una fecha determinada
     * @param int $idPersona
     * @param string $fechaMatricula
     * @return Matricula|null
     */
    public function getMatriculaByPersonaAndFecha(int $idPersona, string $fechaMatricula): ?Matricula;

    /**
     * Obtiene una ficha en formato PDF por ID
     * @param int $id
     */
    public function generateFichaPDF(int $id);

    public function generateCertificadoPDF(int $idMatricula, int $idPrograma);

    public function getModulosPorPagar(int $idMatricula): array;

    public function getModulosPagados(int $idMatricula): array;

    public function generarCronogramaPagos(int $idMatricula);

    /**
     * Elimina una ficha por ID
     * @param int $id
     * @return bool
     */
    public function deleteFichaPDF(int $id): bool;

    /**
     * Crear una nueva matrícula
     * @param MatriculaCreateDTO $matriculaCreateDTO
     * @return Matricula
     */
    public function createMatricula(MatriculaCreateDTO $dto): Matricula;

    public function updateMatricula(int $id, MatriculaUpdateDTO $dto): ?Matricula;
}
