<?php

namespace App\Repositories\Contracts;

use App\Models\Certificado;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ICertificadoRepository {
    /**
     * Obtiene todos los certificados
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator;

    /**
     * Obtener certificado por ID
     * @param int $id
     * @return Certificado|null
     */
    public function findById(int $id): ?Certificado;

    /**
     * Crea un certificado
     * @param array<string, mixed> $data
     * @return Certificado
     */
    public function create(array $data): Certificado;

    /**
     * Actualizar datos de un certificado
     * @param int $id
     * @param array<string, mixed> $data
     * @return Certificado|null
     */
    public function update(int $id, array $data): ?Certificado;

    /**
     * Elimina un certificado por ID
     * @param int $id
     * @return bool 
     */
    public function delete(int $id): bool;
}