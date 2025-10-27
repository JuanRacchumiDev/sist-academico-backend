<?php

namespace App\Services\Contracts;

use App\DTOs\Parametro\ParametroCreateDTO;
use App\DTOs\Parametro\ParametroUpdateDTO;
use App\Models\Parametro;
use Illuminate\Database\Eloquent\Collection;

interface IParametroService {
    /**
     * Obtener todos los parámetros
     * @return Collection<int, Parametro>
     */
    public function getAllParametros(): Collection;

    /**
     * Obtiene un parámetro por clase
     * 
     * @param int $clase
     * @return Parametro|null
     */
    public function getParametroByClase(int $clase): ?Parametro;

    /**
     * Crea un parámetro
     * @param ParametroCreateDTO $parametroCreateDTO
     * @return Parametro
     */
    public function createParametro(ParametroCreateDTO $parametroCreateDTO): Parametro;

    /**
     * Actualiza un parámetro existente
     * @param int $clase
     * @param ParametroUpdateDTO $parametroUpdateDTO
     * @return Parametro|null
     */
    public function updateParametro(int $clase, ParametroUpdateDTO $parametroUpdateDTO): ?Parametro;

    /**
     * Elimina un parámetro
     * @param int $clase
     * @return bool
     */
    public function deleteParametro(int $clase): bool;

    /**
     * Obtiene detalle de un parámetro
     * @param int $clase
     * @return Parametro|null
     */
    public function getParametroWithDetalle(int $clase): ?Parametro;
}