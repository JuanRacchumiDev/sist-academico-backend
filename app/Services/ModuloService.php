<?php
namespace App\Services;

use App\DTOs\Modulo\ModuloCreateDTO;
use App\Models\Modulo;
use App\Repositories\Contracts\IModuloRepository;
use App\Repositories\Contracts\IProgramaRepository;
use App\Services\Contracts\IModuloService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ModuloService implements IModuloService {
    protected IModuloRepository $moduloRepository;
    protected IProgramaRepository $programaRepository;

    public function __construct(IModuloRepository $moduloRepository, IProgramaRepository $programaRepository)
    {
        $this->moduloRepository = $moduloRepository;
        $this->programaRepository = $programaRepository;
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

    public function createModulosBatch(int $idPrograma, array $dtos): Collection
    {
        $programa = $this->programaRepository->findById($idPrograma);
        $limiteMaximo = $programa->numero_modulos;

        // Validar cantidad actual vs nuevos
        $cantidadExistente = $this->moduloRepository->getAllByPrograma($idPrograma)->count();
        $cantidadNuevos = count($dtos);

        if (($cantidadExistente + $cantidadNuevos) > $limiteMaximo) {
            throw new \Exception("Límite excedido. El programa permite máx. {$limiteMaximo} módulos (Ya tiene {$cantidadExistente}).");
        }

        // Persistencia
        $registrados = new Collection();

        foreach($dtos as $dto) {
            $data = $dto->toArray();
            $data['orden'] = $this->moduloRepository->getNumeroOrdenByPrograma($idPrograma);
            $registrados->push($this->moduloRepository->create($data));
        }

        return $registrados;
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