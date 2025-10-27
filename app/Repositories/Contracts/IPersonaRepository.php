<?php

namespace App\Repositories\Contracts;

use App\Models\Persona;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface IPersonaRepository {
    /**
     * Obtener todas las personas
     * @param array<string, mixed>|null $searchParams
     * @return Collection<int, Persona>
     */
    public function getAll(?array $searchParams = null): Collection;

    /**
     * Obtiene todas las personas
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator;

    /**
     * Obtiene todas las personas por grupo
     * @param string $nombreGrupo
     * @return Collection<int, persona>
     */
    public function getAllByGrupo(string $nombreGrupo): Collection;

    /**
     * Obtener una persona por ID
     * @param int $id
     * @return Persona|null
     */
    public function findById(int $id): ?Persona;

    /**
     * Obtener una persona por tipo de documento y número de documento
     * @param int $idTipoDoc
     * @param string $numDoc
     * @return Persona|null
     */
    public function findByTipoDocAndNumDoc(int $idTipoDoc, string $numDoc): ?Persona;

    /**
     * Crea una persona
     * @param array<string, mixed> $data
     * @return Persona
     */
    public function create(array $data): Persona;

    /**
     * Actualizar datos de una persona
     * @param int $id
     * @param array<string, mixed> $data
     * @return Persona|null
     */
    public function update(int $id, array $data): ?Persona;

    /**
     * Elimina una persona por ID
     * @param int $id
     * @return bool 
     */
    public function delete(int $id): bool;
}