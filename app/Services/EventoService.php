<?php
namespace App\Services;

use App\DTOs\Evento\EventoCreateDTO;
use App\DTOs\Evento\EventoUpdateDTO;
use App\Models\Evento;
use App\Repositories\Contracts\IEventoRepository;
use App\Services\Contracts\IEventoService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EventoService implements IEventoService {
    protected IEventoRepository $eventoRepository;

    public function __construct(IEventoRepository $eventoRepository)
    {
        $this->eventoRepository = $eventoRepository;
    }

    /**
     * Obtener todos los eventos
     * @param array<string, mixed>|null $searchParams
     * @return Collection<int, Evento>
     */
    public function getAllEventos(?array $searchParams = null): Collection
    {
        return $this->eventoRepository->getAll($searchParams);
    }

    /**
     * Obtiene todos los eventos con filtros aplicados
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */ 
    public function getAllEventosWithFilters(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->eventoRepository->getAllFiltered($filters, $perPage);
    }

    /**
     * Obtiene eventos por tipo de evento
     * @param int $idTipoEvento
     * @return Collection<int, Evento>
     */
    public function getAllEventosByTipoEvento(int $idTipoEvento): Collection
    {
        return $this->eventoRepository->getAllByTipoEvento($idTipoEvento);   
    }

    /**
     * Obtiene eventos por categoría de evento
     * @param int $idCategoriaEvento
     * @return Collection<int, Evento>
     */
    public function getAllEventosByCategoriaEvento(int $idCategoriaEvento): Collection
    {
        return $this->eventoRepository->getAllByCategoriaEvento($idCategoriaEvento);
    }

    /**
     * Obtiene un evento por ID
     * @param int $id
     * @return Evento|null
     */
    public function getEventoById(int $id): ?Evento
    {
        return $this->eventoRepository->findById($id);
    }

    /**
     * Crear una nuevo evento
     * @param EventoCreateDTO $eventoCreateDTO
     * @return Evento
     */
    public function createEvento(EventoCreateDTO $eventoCreateDTO): Evento
    {
        $data = array_filter($eventoCreateDTO->toArray(), fn($value) => !is_null($value));

        return $this->eventoRepository->create($data);
    }

    /**
     * Actualizar un evento existente
     * @param int $id
     * @param EventoUpdateDTO $eventoUpdateDTO
     * @return Evento|null
     */
    public function updateEvento(int $id, EventoUpdateDTO $eventoUpdateDTO): ?Evento
    {
        $data = array_filter($eventoUpdateDTO->toArray(), fn($value) => !is_null($value));

        return $this->eventoRepository->update($id, $data);
    }

    /**
     * Elimina un evento
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function deleteEvento(int $id): bool
    {
        $evento = $this->eventoRepository->findById($id);

        if (!$evento) {
            return false;
        }

        return $this->eventoRepository->delete($id);
    }
}