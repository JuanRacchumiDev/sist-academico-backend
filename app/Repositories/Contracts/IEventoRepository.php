<?php
namespace App\Repositories\Contracts;

use App\Models\Evento;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IEventoRepository
{
    /**
     * Obtener todos los eventos
     * @param array<string, mixed>|null $searchParams
     * @return Collection<int, Evento>
     */
    public function getAll(?array $searchParams = null): Collection;

    /**
     * Obtiene todos los eventos
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator;

    /**
     * Obtener eventos por tipo de evento
     * @param int $id_tipoevento
     * @return Collection<int, Evento>
     */
    public function getAllByTipoEvento(int $id_tipoevento): Collection;

    /**
     * Obtener eventos por categoría de evento
     * @param int $id_categoriaevento
     * @return Collection<int, Evento>
     */
    public function getAllByCategoriaEvento(int $id_categoriaevento): Collection;

    /**
     * Obtener evento por id
     * @param int $id
     * @return Evento|null
     */
    public function findById(int $id): ?Evento;

    /**
     * Crea un evento
     * @param array<string, mixed> $data
     * @return Evento
     */
    public function create(array $data): Evento;

    /**
     * Actualizar datos de un evento
     * @param int $id
     * @param array<string, mixed> $data
     * @return Evento|null
     */
    public function update(int $id, array $data): ?Evento;

    /**
     * Eliminar un eventi por su ID
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}