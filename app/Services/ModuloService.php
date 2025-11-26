<?php
namespace App\Services;

use App\DTOs\Modulo\ModuloCreateDTO;
use App\Models\Modulo;
use App\Repositories\Contracts\IModuloRepository;
use App\Services\Contracts\IModuloService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ModuloService implements IModuloService {
    protected IModuloRepository $moduloRepository;

    public function __construct(IModuloRepository $moduloRepository)
    {
        $this->moduloRepository = $moduloRepository;
    }

    /**
     * Obtener todos los módulos
     * @param array<string, mixed>|null $searchParams
     * @return Collection<int, Modulo>
     */
    public function getAllModulos(?array $searchParams = null): Collection
    {
        return $this->moduloRepository->getAll($searchParams);
    }

    /**
     * Obtiene todos los módulos con filtros aplicados
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */ 
    public function getAllModulosWithFilters(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->moduloRepository->getAllFiltered($filters, $perPage);
    }

    /**
     * Obtiene módulos por programa
     * @param int $idPrograma
     * @return Collection<int, Modulo>
     */
    public function getAllModulosByPrograma(int $idPrograma): Collection
    {
        return $this->moduloRepository->getAllByPrograma($idPrograma);   
    }
    /**
     * Obtiene un módulo por ID
     * @param int $id
     * @return Modulo|null
     */
    public function getModuloById(int $id): ?Modulo
    {
        return $this->moduloRepository->findById($id);
    }

    /**
     * Crear una nuevo módulo
     * @param ModuloCreateDTO $moduloCreateDTO
     * @return Modulo
     */
    public function createModulo(ModuloCreateDTO $moduloCreateDTO): Modulo
    {
        $data = array_filter($moduloCreateDTO->toArray(), fn($value) => !is_null($value));
        
        $idPrograma = $data['id_programa'];
        
        $data['orden'] = $this->moduloRepository->getNumeroOrdenByPrograma($idPrograma);

        return $this->moduloRepository->create($data);
    }
}