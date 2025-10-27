<?php

namespace App\Services\Contracts;

use App\DTOs\DetalleParametro\DetalleParametroCreateDTO;
use App\DTOs\DetalleParametro\DetalleParametroUpdateDTO;
use App\Models\DetalleParametro;
use Illuminate\Database\Eloquent\Collection;

interface IDetalleParametroService {

    /**
     * Obtiene todos los registros relacionados a un parámetro por clase
     * @param int $clase
     * @return Collection<int, DetalleParametro>
     */
    public function getAllByClase(int $clase): Collection;

    /**
     * Obtiene DetalleParametros filtrados
     * @param array<int, mixed> $filters
     * @return Collection<int, DetalleParametro>
     */
    public function getAllFiltered(array $filters): Collection;

    /**
     * Obtiene un detalle por ID
     * @param int $codigo
     * @return DetalleParametro|null
     */
    public function getByCodigo(int $codigo): ?DetalleParametro;

    /**
     * Obtiene un detalle por clase y código
     * @param int $clase
     * @param int $codigo
     */
    public function getByClaseAndCodigo(int $clase, int $codigo): ?DetalleParametro;

    /**
     * Crea un nuevo detalle parámetro
     * @param DetalleParametroCreateDTO $detalleParametroCreateDTO
     * @return DetalleParametro
     */
    public function createDetalle(DetalleParametroCreateDTO $detalleParametroCreateDTO): DetalleParametro;

    /**
     * Actualiza un detalle parámetro
     * @param int $codigo
     * @param DetalleParametroUpdateDTO $detalleParametroUpdateDTO
     * @return DetalleParametro|null
     */
    public function updateDetalle(int $codigo, DetalleParametroUpdateDTO $detalleParametroUpdateDTO): ?DetalleParametro;

    /**
     * Elimina un detalle parámetro
     * @param int $codigo
     * @return bool
     */
    public function deleteDetalle(int $codigo): bool;
}