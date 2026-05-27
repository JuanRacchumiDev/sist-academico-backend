<?php

namespace App\Services;

use App\DTOs\Persona\PersonaCreateDTO;
use App\DTOs\Persona\PersonaUpdateDTO;
use App\Models\Persona;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\IPersonaRepository;
use App\Repositories\Contracts\IDetalleParametroRepository;
use App\Services\Contracts\IPersonaService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PersonaService implements IPersonaService
{
    protected IPersonaRepository $personaRepository;
    protected IDetalleParametroRepository $detalleRepository;

    public function __construct(IPersonaRepository $personaRepository, IDetalleParametroRepository $detalleRepository)
    {
        $this->personaRepository = $personaRepository;
        $this->detalleRepository = $detalleRepository;
    }

    /**
     * Obtener todas las personas
     * @param array<string, mixed>|null $searchParams
     * @return Collection<int, Persona>
     */
    public function getAllPersonas(?array $searchParams = null): Collection
    {
        return $this->personaRepository->getAll($searchParams);
    }

    /**
     * Obtiene todos las personas con filtros aplicados
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllPersonasWithFilters(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->personaRepository->getAllFiltered($filters, $perPage);
    }

    public function getAllPersonasForSearch(array $filters): Collection
    {
        $searchTerm = $filters['search'] ?? '';

        // Si hay búsqueda, debe tener al menos 3 caracteres
        if (!empty($searchTerm) && strlen($searchTerm) < 3) {
            return new Collection();
        }

        // Si no hay búsqueda, limitamos a 50 registros
        $limit = empty($searchTerm) ? 50 : null;

        return $this->personaRepository->getByGrupo($filters, $limit);
    }

    /**
     * Obtiene una persona por ID
     * @param int $id
     * @return Persona|null
     */
    public function getPersonaById(int $id): ?Persona
    {
        return $this->personaRepository->findById($id);
    }

    /**
     * Crear una nueva persona
     * @param PersonaCreateDTO $personaCreateDTO
     * @return Persona
     */
    public function createPersona(PersonaCreateDTO $personaCreateDTO): Persona
    {
        return DB::transaction(function () use ($personaCreateDTO) {
            $dataToCreate = $personaCreateDTO->toArray();

            // Obtener el nombre de grupo
            $nombreGrupo = $personaCreateDTO->nombre_grupo;

            // Filtrar nulos
            $data = array_filter($dataToCreate, fn($value) => !is_null($value));

            // Crear la persona
            /** @var Persona $persona */
            $persona = $this->personaRepository->create($data);

            // Obteniendo grupo
            $grupo = $this->detalleRepository->findByNombreUrl($nombreGrupo);

            // Adjuntar el grupo (si existe el código)
            if ($grupo) {
                $codigoGrupo = $grupo->codigo;

                $persona->grupos()->attach($codigoGrupo);
            }

            return $persona;
        });
    }

    /**
     * Actualizar una persona existente
     * @param int $id
     * @param PersonaUpdateDTO $personaUpdateDTO
     * @return Persona|null
     */
    public function updatePersona(int $id, PersonaUpdateDTO $personaUpdateDTO): ?Persona
    {
        $data = array_filter($personaUpdateDTO->toArray(), fn($value) => !is_null($value));

        return $this->personaRepository->update($id, $data);
    }

    /**
     * Elimina una persona
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function deletePersona(int $id): bool
    {
        $persona = $this->personaRepository->findById($id);

        if (!$persona) {
            return false;
        }

        return $this->personaRepository->delete($id);
    }
}
