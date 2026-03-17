<?php
namespace App\Services\Contracts;

use App\DTOs\Persona\PersonaCreateDTO;
use App\DTOs\Persona\PersonaUpdateDTO;
use App\Models\Persona;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IPersonaService {
    /**
     * Obtener todas las personas
     * @param array<string, mixed>|null $searchParams
     * @return Collection<int, Persona>
     */
    public function getAllPersonas(?array $searchParams = null): Collection;

    /**
     * Obtiene todas las personas con filtros aplicados
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator<Persona>
     */
    public function getAllPersonasWithFilters(array $filters, int $perPage): LengthAwarePaginator;

    /**
     * Obtiene una persona por ID
     * @param int $id
     * @return Persona|null
     */
    public function getPersonaById(int $id): ?Persona;

    /**
     * Crear una nueva persona
     * @param PersonaCreateDTO $personaCreateDTO
     * @return Persona
     */
    public function createPersona(PersonaCreateDTO $personaCreateDTO): Persona;

    /**
     * Actualiza una persona existente
     * @param int $id
     * @param PersonaUpdateDTO $personaUpdateDTO
     * @return Persona|null
     */
    public function updatePersona(int $id, PersonaUpdateDTO $personaUpdateDTO): ?Persona;

    /**
     * Elimina una persona
     * @param int $id
     * @return bool
     */
    public function deletePersona(int $id): bool;
}