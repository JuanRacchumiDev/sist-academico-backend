<?php
namespace App\Services\Contracts;

use App\DTOs\Evento\EventoCreateDTO;
use App\DTOs\Evento\EventoUpdateDTO;
use App\Models\Evento;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IEventoService {
    /**
     * Obtener todos los eventos
     * @param array<string, mixed>|null $searchParams
     * @return Collection<int, Evento>
     */
    public function getAllEventos(?array $searchParams = null): Collection;

    /**
     * Obtiene todos los eventos con filtros aplicados
     * @param array<string, mixed> $filters
     * @param int $perPage
     */
    public function getAllEventosWithFilters(array $filters, int $perPage): LengthAwarePaginator;

    /**
     * Obtener los eventos por tipo de evento
     * @param int $idTipoEvento
     * @return Collection<int, Evento>
     */
    public function getAllEventosByTipoEvento(int $idTipoEvento): Collection;

    /**
     * Obtener los eventos por categoría de evento
     * @param int $idCategoriaEvento
     * @return Collection<int, Evento>
     */
    public function getAllEventosByCategoriaEvento(int $idCategoriaEvento): Collection;

    /**
     * Obtiene un evento por ID
     * @param int $id
     * @return Evento|null
     */
    public function getEventoById(int $id): ?Evento;

    /**
     * Crear un nuevo evento
     * @param eventoCreateDTO $eventoCreateDTO
     * @return Evento
     */
    public function createEvento(EventoCreateDTO $eventoCreateDTO): Evento;

    /**
     * Actualiza un evento existente
     * @param int $id
     * @param EventoUpdateDTO $eventoUpdateDTO
     * @return Evento|null
     */
    public function updateEvento(int $id, EventoUpdateDTO $eventoUpdateDTO): ?Evento;

    /**
     * Elimina un evento
     * @param int $id
     * @return bool
     */
    public function deleteEvento(int $id): bool;
}