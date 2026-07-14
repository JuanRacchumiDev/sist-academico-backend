<?php

namespace App\Services;

use App\DTOs\Modulo\ModuloCreateDTO;
use App\DTOs\Modulo\ModuloUpdateDTO;
use App\Models\Modulo;
use App\Repositories\Contracts\IModuloRepository;
use App\Repositories\Contracts\IProgramaRepository;
use App\Services\Contracts\IModuloService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Override;

class ModuloService implements IModuloService
{
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
        Log::info('Iniciando proceso de registro de módulos', ['dtos' => $dtos]);

        $programa = $this->programaRepository->findById($idPrograma);

        $limiteMaximo = (int) ($programa->numero_modulos ?? 0);

        // Validar cantidad actual vs nuevos
        $cantidadExistente = $this->moduloRepository->getAllByPrograma($idPrograma)->count();
        $cantidadNuevos = count($dtos);

        // Solo validamos el límite si el programa tiene un límite mayor a 0 configurado
        if ($limiteMaximo > 0 && ($cantidadExistente + $cantidadNuevos) > $limiteMaximo) {
            throw new \Exception("Límite excedido. El programa permite máx. {$limiteMaximo} módulos (Ya tiene {$cantidadExistente}).");
        }

        // Persistencia
        $registrados = new Collection();

        foreach ($dtos as $dto) {
            $data = $dto->toArray();
            $data['orden'] = $this->moduloRepository->getNumeroOrdenByPrograma($idPrograma);
            Log::info('Ítem data módulo', ['data' => $data]);
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

    public function updateModulo(int $id, ModuloUpdateDTO $moduloUpdateDTO): ?Modulo
    {
        $data = array_filter($moduloUpdateDTO->toArray(), fn($value) => !is_null($value));

        return $this->moduloRepository->update($id, $data);
    }

    public function syncModulosPrograma(int $idPrograma, array $dtos): Collection
    {
        return DB::transaction(function () use ($idPrograma, $dtos) {
            $procesados = new Collection();

            foreach ($dtos as $dto) {
                $data = array_filter($dto, fn($value) => !is_null($value));

                $idModulo = $dto['id'] ?? null;

                if (!empty($idModulo)) {
                    $moduloActualizado = $this->moduloRepository->update($idModulo, $data);

                    if ($moduloActualizado) {
                        $procesados->push($moduloActualizado);
                    }
                } else {
                    $data['id_programa'] = $idPrograma;

                    if (!isset($data['orden'])) {
                        $data['orden'] = $this->moduloRepository->getNumeroOrdenByPrograma($idPrograma);
                    }

                    $procesados->push($this->moduloRepository->create($data));
                }
            }

            return $procesados;
        });
    }
}
