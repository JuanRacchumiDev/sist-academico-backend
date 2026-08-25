<?php

namespace App\Services;

use App\DTOs\Institucion\InstitucionCreateDTO;
use App\DTOs\Institucion\InstitucionUpdateDTO;
use App\Models\Institucion;
use App\Repositories\Contracts\IInstitucionRepository;
use App\Services\Contracts\IInstitucionService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use Override;

class InstitucionService implements IInstitucionService
{
    protected IInstitucionRepository $institucionRepository;

    public function __construct(IInstitucionRepository $institucionRepository)
    {
        $this->institucionRepository = $institucionRepository;
    }

    public function getAll(): Collection
    {
        return $this->institucionRepository->getAll();
    }

    /**
     * Obtiene instituciones filtrados
     * @param array<int, mixed> $filters
     * @return Collection<int, Institucion>
     */
    public function getAllFiltered(array $filters): Collection
    {
        return $this->institucionRepository->getAllFiltered($filters);
    }

    /**
     * Obtiene instituciones filtrados
     * @param array<int, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator<Institucion>
     */
    public function getAllFilteredPaginate(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->institucionRepository->getAllFilteredPaginate($filters, $perPage);
    }

    public function getInstitucionById(int $id): ?Institucion
    {
        return $this->institucionRepository->findById($id);
    }

    public function createInstitucion(InstitucionCreateDTO $institucionCreateDTO): Institucion
    {
        Log::info('Iniciando creación de institución', [
            'institucionCreateDTO' => $institucionCreateDTO->toArray()
        ]);

        return DB::transaction(function () use ($institucionCreateDTO) {
            $dataToCreate = $institucionCreateDTO->toArray();
            Log::info('Evaluando variable $dataToCreate', ['dataToCreate' => $dataToCreate]);

            // Filtrar nulos
            $data = array_filter($dataToCreate, fn($value) => !is_null($value));

            $institucion = $this->institucionRepository->create($data);

            return $institucion;
        });
    }

    public function updateInstitucion(int $id, InstitucionUpdateDTO $institucionUpdateDTO): ?Institucion
    {
        $data = array_filter($institucionUpdateDTO->toArray(), fn($value) => !is_null($value));

        return $this->institucionRepository->update($id, $data);
    }
}
