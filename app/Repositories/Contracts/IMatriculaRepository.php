<?php

namespace App\Repositories\Contracts;

use App\Models\Matricula;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IMatriculaRepository
{
    /**
     * Obtener todas las matrículas
     * @param array<string, mixed>|null $searchParams
     * @return Collection<int, Matricula>
     */
    public function getAll(?array $searchParams = null): Collection;

    /**
     * Obtiene todas las matrículas
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator;

    /**
     * Obtiene un certificado por matrícula
     * @param int $idMatricula
     * @param int $idPrograma
     * @return object
     */
    public function getCertificado(int $idMatricula, int $idPrograma): ?object;

    /**
     * Obtener una matrícula por ID
     * @param int $id
     * @return Matricula|null
     */
    public function findById(int $id): ?Matricula;

    /**
     * Crea una matrícula
     * @param array<string, mixed> $data
     * @return Matricula
     */
    public function create(array $data): Matricula;

    /**
     * Actualizar datos de una matrícula
     * @param int $id
     * @param array<string, mixed> $data
     * @return Matricula|null
     */
    public function update(int $id, array $data): ?Matricula;

    /**
     * Eliminar una matrícula por su ID
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
