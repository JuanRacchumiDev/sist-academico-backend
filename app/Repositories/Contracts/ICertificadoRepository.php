<?php

namespace App\Repositories\Contracts;

use App\Models\Certificado;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ICertificadoRepository {
    /**
     * Obtener todos los certificados
     * @param array<string, mixed>|null $searchParams
     * @return Collection<int, Certificado>
     */
    public function getAll(?array $searchParams = null): Collection;

    /**
     * Obtiene todos los certificados
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator;

    /**
     * Obtener certificados por tipo certificado
     * @param int $id_tipocertificado
     * @return Collection<int, Certificado>
     */
    public function getAllByTipoCertificado(int $id_tipocertificado): Collection;

    /**
     * Obtener certificados por evento
     * @param int $id_evento
     * @return Collection<int, Certificado>
     */
    public function getAllByEvento(int $id_evento): Collection;

    /**
     * Obtener certificados por persona
     * @param int $id_persona
     * @return Collection<int, Certificado>
     */
    public function getAllByPersona(int $id_persona): Collection;

    /**
     * Obtener certificados por plantilla
     * @param int $id_plantilla
     * @return Collection<int, Certificado>
     */
    public function getAllByPlantilla(int $id_plantilla): Collection;

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