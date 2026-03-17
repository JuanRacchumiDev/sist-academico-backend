<?php

namespace App\Repositories\Contracts;

use App\DTOs\Persona\PersonaAPIDTO;
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
     * Obtener una persona por ID
     * @param int $id
     * @return Persona|null
     */
    public function findById(int $id): ?Persona;

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

    public function updateOrCreateFromAPI(PersonaAPIDTO $dto): Persona;

    public function syncGrupos(Persona $persona, array $grupoIds): void;
}