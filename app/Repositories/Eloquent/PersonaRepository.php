<?php

namespace App\Repositories\Eloquent;

use App\DTOs\Persona\PersonaAPIDTO;
use App\Models\DetalleParametro;
use App\Models\Persona;
use App\Repositories\Contracts\IPersonaRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

class PersonaRepository implements IPersonaRepository
{
    /**
     * Obtiene todas las personas
     * @param array<string, mixed>|null $searchParams
     * @return Collection<int, Persona>
     */
    public function getAll(?array $searchParams = null): Collection
    {
        $query = Persona::with($this->getEagerLoads());

        $query = $this->applyFilters($query, $searchParams ?? []);

        return $query->get();
    }

    /**
     * Obtiene todos las personas con filtros aplicados.
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator<Persona>
     */
    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = Persona::with($this->getEagerLoads());

        $query = $this->applyFilters($query, $filters);

        return $query->orderBy('apellido_paterno', 'ASC')->paginate($perPage);
    }

    public function getByGrupo(array $filters, ?int $limit = null): Collection
    {
        $query = Persona::with($this->getEagerLoads());

        $query = $this->applyFilters($query, $filters);

        if ($limit) {
            $query->limit($limit);
        }

        return $query->orderBy('apellido_paterno', 'ASC')->get();
    }

    /**
     * Busca una persona por su ID
     * @param int $id
     * @return Persona|null
     */
    public function findById(int $id): ?Persona
    {
        return Persona::with($this->getEagerLoads())->findOrFail($id);
    }

    /**
     * Crear una persona
     * @param array<string, mixed> $data
     * @return Persona
     */
    public function create(array $data): Persona
    {
        // Crear el registro de persona
        $persona = Persona::create($data);

        // Obteniendo el código de la clase grupo
        $clase = config('params.clases.grupo');

        if (isset($data['nombre_grupo'])) {
            $grupo = DetalleParametro::where('nombre_url', $data['nombre_grupo'])
                ->where('parametro_clase', $clase)
                ->first();

            if ($grupo) {
                $persona->grupos()->attach(
                    $grupo->codigo,
                    [
                        'user_crea' => $data['user_crea'] ?? 'systemapi'
                    ]
                );
            }
        }

        return $persona;
    }

    /**
     * Actualiza una persona existente
     * @param int $id
     * @param array<string, mixed> $data
     * @return Persona|null
     */
    public function update(int $id, array $data): ?Persona
    {
        $persona = $this->findById($id);

        if (!$persona) {
            return null;
        }

        $persona->update($data);

        // Opcional: Actualizar grupos
        if (isset($data['grupos_ids'])) {
            $this->syncGrupos($persona, $data['grupos_ids']);
        }

        return $persona;
    }

    public function syncGrupos(Persona $persona, array $grupoIds): void
    {
        $persona->grupos()->sync($grupoIds);
    }

    /**
     * Elimina una persona por su ID
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $persona = $this->findById($id);

        return $persona ? $persona->delete() : false;
    }

    public function updateOrCreateFromAPI(PersonaAPIDTO $dto): Persona
    {
        $persona = Persona::firstOrNew([
            'id_tipodocumento' => $dto->id_tipodocumento,
            'numero_documento' => $dto->numero_documento
        ]);

        // Mapear el DTO a los datos que serán guardados/actualizados
        $dataToUpdate = [
            'nombres'           => $dto->nombres,
            'apellido_paterno'  => $dto->apellido_paterno,
            'apellido_materno'  => $dto->apellido_materno,
            'nombre_completo'   => $dto->nombre_completo,
            'departamento'      => $dto->departamento,
            'provincia'         => $dto->provincia,
            'distrito'          => $dto->distrito,
            'direccion'         => $dto->direccion,
            'direccion_completa' => $dto->direccion_completa,
            'ubigeo_reniec'     => $dto->ubigeo_reniec,
            'ubigeo'            => $dto->ubigeo, // El código de ubigeo final
            'fecha_nacimiento'  => $dto->fecha_nacimiento, // Ya transformado a YYYY-MM-DD
            'estado_civil'      => $dto->estado_civil,
            'sexo'              => $dto->sexo,
            'origen'            => $dto->origen,
            'user_crea'         => $dto->user_crea ?? null,
            'user_actualiza'    => $dto->user_actualiza ?? null,
            'user_elimina'      => $dto->user_elimina ?? null
        ];

        $persona->fill($dataToUpdate);
        $persona->save();

        return $persona;
    }

    private function applyFilters(Builder $query, array $filters): Builder
    {
        if (isset($filters['id_tipodocumento'])) {
            $query->where('id_tipodocumento', $filters['id_tipodocumento']);
        }

        if (isset($filters['numero_documento'])) {
            $query->where('numero_documento', $filters['numero_documento']);
        }

        if (isset($filters['estado'])) {
            $query->where('estado', filter_var($filters['estado'], FILTER_VALIDATE_BOOLEAN));
        }

        if (isset($filters['grupo'])) {
            $query->whereHas('grupos', function ($q) use ($filters) {
                $q->where('nombre_url', $filters['grupo']);
            });
        }

        if (isset($filters['search'])) {
            $search = '%' . strtolower($filters['search']) . '%';
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(numero_documento) LIKE ?', [$search])
                    ->orWhereRaw('LOWER(nombre_completo) LIKE ?', [$search])
                    ->orWhereRaw('LOWER(email) LIKE ?', [$search]);
            });
        }

        return $query;
    }

    private function getEagerLoads(): array
    {
        return [
            'tipoDocumento',
            'grupos',
            'matriculas.estadoMatricula',
            'matriculas.institucion',
            'matriculas.detalles.programa' => function ($query) {
                $query->with(['tipoPrograma', 'adjuntos' => function ($q) {
                    $q->where('estado', true)->where('es_descargable', true);
                }]);
            },
            'certificados'
        ];
    }
}
