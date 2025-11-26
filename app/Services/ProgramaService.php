<?php
namespace App\Services;

use App\DTOs\Programa\ProgramaCreateDTO;
use App\Models\Programa;
use App\Repositories\Contracts\IProgramaRepository;
use App\Services\Contracts\IProgramaService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProgramaService implements IProgramaService {
    protected IProgramaRepository $programaRepository;

    public function __construct(IProgramaRepository $programaRepository)
    {
        $this->programaRepository = $programaRepository;
    }

    /**
     * Obtener todos los programas
     * @param array<string, mixed>|null $searchParams
     * @return Collection<int, Programa>
     */
    public function getAllProgramas(?array $searchParams = null): Collection
    {
        return $this->programaRepository->getAll($searchParams);
    }

    /**
     * Obtiene todos los programas con filtros aplicados
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */ 
    public function getAllProgramasWithFilters(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->programaRepository->getAllFiltered($filters, $perPage);
    }

    /**
     * Obtiene un programa por ID
     * @param int $id
     * @return Programa|null
     */
    public function getProgramaById(int $id): ?Programa
    {
        return $this->programaRepository->findById($id);
    }

    /**
     * Crear una nuevo programa
     * @param ProgramaCreateDTO $programaCreateDTO
     * @return Programa
     */
    public function createPrograma(ProgramaCreateDTO $programaCreateDTO): Programa
    {
        if ($programaCreateDTO->plan instanceof UploadedFile) {
            // Obtener el nombre original del archivo
            $originalName = $programaCreateDTO->plan->getClientOriginalName();

            // Almacenar el archivo PDF
            $path = $programaCreateDTO->plan->storeAs(
                'programas',
                $originalName,
                'public'
            );

            $programaCreateDTO->plan = $path;
        } else {
            $programaCreateDTO->plan = null;
        }

        $programaData = $programaCreateDTO->toArray();

        return $this->programaRepository->create($programaData);
    }
}