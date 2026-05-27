<?php
namespace App\Services\Contracts;

use App\DTOs\Programa\ProgramaCreateDTO;
use App\DTOs\Programa\ProgramaUpdateDTO;
use App\Models\Programa;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IProgramaService {
    /**
     * Obtener todos los programas
     * @param array<string, mixed>|null $searchParams
     * @return Collection<int, Programa>
     */
    public function getAllProgramas(?array $searchParams = null): Collection;

    /**
     * Obtiene todos los programas con filtros aplicados
     * @param array<string, mixed> $filters
     * @param int $perPage
     */
    public function getAllProgramasWithFilters(array $filters, int $perPage): LengthAwarePaginator;

    /**
     * Obtiene un programa por ID
     * @param int $id
     * @return Programa|null
     */
    public function getProgramaById(int $id): ?Programa;

    /**
     * Crear un nuevo programa
     * @param ProgramaCreateDTO $programaCreateDTO
     * @return Programa
     */
    public function createPrograma(ProgramaCreateDTO $programaCreateDTO): Programa;

    /**
     * Actualiza un programa existente
     * @param int $id
     * @param ProgramaUpdateDTO $programaUpdateDTO
     * @return Programa|null
     */
    public function updatePrograma(int $id, ProgramaUpdateDTO $programaUpdateDTO): ?Programa;
}