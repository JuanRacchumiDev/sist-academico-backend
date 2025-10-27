<?php

namespace App\Repositories\Eloquent;

use App\Models\DetalleParametro;
use App\Models\Persona;
use App\Repositories\Contracts\IPersonaRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class PersonaRepository implements IPersonaRepository {
    /**
     * Obtiene todas las personas
     * @param array<string, mixed>|null $searchParams
     * @return Collection<int, Persona>
     */
    public function getAll(?array $searchParams = null): Collection
    {
        $query = Persona::with([
            'tipoDocumento'
        ]);

        if ($searchParams) {
            $query->where(function($q) use ($searchParams) {
                if (isset($searchParams['id_tipodocumento'])) {
                    $q->where('id_tipodocumento', $searchParams['id_tipodocumento']);
                }

                if (isset($searchParams['search'])) {
                    $search = '%'.strtolower($searchParams['search']).'%';

                    $q->whereRaw('LOWER(numero_documento) LIKE ?', [$search])
                        ->orWhereRaw('LOWER(nombres) LIKE ?', [$search])
                        ->orWhereRaw('LOWER(apellido_paterno) LIKE ?', [$search])
                        ->orWhereRaw('LOWER(apellido_materno) LIKE ?', [$search])
                        ->orWhereRaw('LOWER(nombre_completo) LIKE ?', [$search]);
                }
            });
        }

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
        $query = Persona::with([
            'tipoDocumento'
        ]);

        // Aplicar filtros directos
        if (isset($filters['id_tipodocumento'])) {
            $query->where('id_tipodocumento', $filters['id_tipodocumento']);
        }

        if (isset($filters['estado'])) {
            $query->where('estado', (bool)$filters['estado']);
        }

        // Aplicar búsqueda por texto
        if (isset($filters['search'])) {
            $search = '%'.strtolower($filters['search']).'%';
            
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(numero_documento) LIKE ?', [$search])
                    ->orWhereRaw('LOWER(nombres) LIKE ?', [$search])
                    ->orWhereRaw('LOWER(apellido_paterno) LIKE ?', [$search])
                    ->orWhereRaw('LOWER(apellido_materno) LIKE ?', [$search])
                    ->orWhereRaw('LOWER(nombre_completo) LIKE ?', [$search]);
            });
        }

        $query->orderBy('apellido_paterno', 'asc')
            ->orderBy('apellido_materno', 'asc')
            ->orderBy('nombres', 'asc');

        return $query->paginate($perPage);
    }

    public function getAllByGrupo(string $nombreGrupo): Collection
    {
        // throw ValidationException::withMessages(['nombreGrupo' => $nombreGrupo, 'location' => 'personaRepository']);

        return Persona::with(['tipoDocumento'])->whereHas('grupos', function($query) use ($nombreGrupo) {
            $query->where('nombre_url', $nombreGrupo);
        })->get();
    }

    /**
     * Busca una persona por su ID
     * @param int $id
     * @return Persona|null
     */
    public function findById(int $id): ?Persona
    {
        // return Persona::with('tipoDocumento')->find($id);
        return Persona::with(['tipoDocumento', 'grupos'])->findOrFail($id);
    }

    /**
     * Busca una persona por idTipoDoc y numDoc
     * @param int $idTipoDoc
     * @param string $numDoc
     * @return Persona|null
     */
    public function findByTipoDocAndNumDoc(int $idTipoDoc, string $numDoc): ?Persona
    {
        return Persona::with('tipoDocumento')
            ->where('id_tipodocumento', $idTipoDoc)
            ->where('numero_documento',$numDoc)
            ->first();
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

        // Obtener el código del grupo
        $grupo = DetalleParametro::where('nombre_url', $data['nombre_grupo'])
            ->where('parametro_clase', 1010)
            ->firstOrFail();

        $persona->grupos()->attach($grupo->codigo);

        return $persona;

        // return Persona::create($data);
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

        if ($persona) {
            $persona->update($data);

            if ($data['nombre_grupo']) {
                $nuevoGrupo = DetalleParametro::where('nombre_url', $data['nombre_grupo'])
                    ->where('parametro_clase', 1010)
                    ->firstOrFail();

                $persona->grupos()->sync([$nuevoGrupo->codigo]);

                return $persona->refresh();
            }

            // return $persona;
        }
        
        return null;
    }

    /**
     * Elimina una persona por su ID
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $persona = $this->findById($id);

        if ($persona) {
            return $persona->delete();
        }
        
        return false;
    }
}

