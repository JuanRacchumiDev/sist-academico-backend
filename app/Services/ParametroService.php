<?php

namespace App\Services;

use App\DTOs\Parametro\ParametroCreateDTO;
use App\DTOs\Parametro\ParametroUpdateDTO;
use App\Models\Parametro;
use App\Repositories\Contracts\IParametroRepository;
use App\Services\Contracts\IParametroService;
use Illuminate\Database\Eloquent\Collection;

class ParametroService implements IParametroService {
    protected IParametroRepository $parametroRepository;

    public function __construct(IParametroRepository $parametroRepository)
    {
        $this->parametroRepository = $parametroRepository;
    }

    /**
     * Obtener todos los parámetro.
     *
     * @return Collection<int, Parametro>
     */
    public function getAllParametros(): Collection
    {
        return $this->parametroRepository->getAll();
    }

    /**
     * Obtiene un parámetro por clase.
     *
     * @param int $clase
     * @return Parametro|null
     */
    public function getParametroByClase(int $clase): ?Parametro
    {
        return $this->parametroRepository->findByClase($clase);
    }

    /**
     * Crea un nuevo parámetro.
     *
     * @param ParametroCreateDTO $parametroCreateDTO
     * @return Parametro
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createParametro(ParametroCreateDTO $parametroCreateDTO): Parametro
    {
        $nextParClase = $this->parametroRepository->getNextParClase();
        
        $parametroCreateDTO->clase = $nextParClase;

        $data = array_filter($parametroCreateDTO->toArray(), fn($value) => !is_null($value));
        
        return $this->parametroRepository->create($data);
    }

    /**
     * Actualizar un parámetro existente.
     *
     * @param int $clase
     * @param ParametroUpdateDTO $parametroUpdateDTO
     * @return Parametro|null
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateParametro(int $clase, ParametroUpdateDTO $parametroUpdateDTO): ?Parametro
    {
        $data = array_filter($parametroUpdateDTO->toArray(), fn($value) => !is_null($value));

        return $this->parametroRepository->update($clase, $data);
    }

    /**
     * Elimina un parámetro.
     *
     * @param int $clase
     * @return bool
     * @throws \Exception
     */
    public function deleteParametro(int $clase): bool
    {
        $parametro = $this->parametroRepository->findByClase($clase);

        if (!$parametro) {
            return false;
        }

        return $this->parametroRepository->delete($clase);
    }

    /**
     * Obtiene detalle de un parámetro
     * @param int $clase
     * @return Parametro|null
     */
    public function getParametroWithDetalle(int $clase): ?Parametro
    {
        return $this->parametroRepository->getWithDetalle($clase);
    }
}