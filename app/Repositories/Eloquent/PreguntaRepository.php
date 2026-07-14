<?php

namespace App\Repositories\Eloquent;

use App\Models\Pregunta;
use App\Repositories\Contracts\IPreguntaRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Override;

class PreguntaRepository implements IPreguntaRepository
{
    public function getAll(?array $searchParams = null): Collection
    {
        $query = Pregunta::with([
            'cuestionario',
            'opciones',
            'respuestas'
        ]);

        $query = $this->applyFilters($query, $searchParams ?? []);

        return $query->orderBy('id', 'DESC')->get();
    }

    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = Pregunta::with([
            'cuestionario',
            'opciones',
            'respuestas'
        ]);

        $query = $this->applyFilters($query, $filters);

        return $query->orderBy('id', 'DESC')->paginate($perPage);
    }

    public function findById(int $id): ?Pregunta
    {
        return Pregunta::with([
            'cuestionario',
            'opciones',
            'respuestas'
        ])->findOrFail($id);
    }

    public function create(array $data): Pregunta
    {
        $pregunta = Pregunta::create($data);
        return $pregunta;
    }

    public function update(int $id, array $data): ?Pregunta
    {
        $pregunta = $this->findById($id);

        if (!$pregunta) {
            return null;
        }

        $pregunta->update($data);

        return $pregunta;
    }

    public function delete(int $id): bool
    {
        $pregunta = $this->findById($id);

        return $pregunta ? $pregunta->delete() : false;
    }

    public function createMany(int $cuestionarioId, array $preguntasData): void
    {
        foreach ($preguntasData as $pregunta) {
            Pregunta::create(array_merge($pregunta, ['id_cuestionario' => $cuestionarioId]));
        }
    }

    public function syncPreguntas(int $cuestionarioId, array $preguntasData): void
    {
        $existingIds = collect($preguntasData)->pluck('id')->filter()->toArray();

        Pregunta::where('id_cuestionario', $cuestionarioId)
            ->whereNotIn('id', $existingIds)
            ->delete();

        foreach ($preguntasData as $data) {
            if (isset($data['id'])) {
                Pregunta::where('id', $data['id'])->update($data);
            } else {
                Pregunta::create(array_merge($data, ['id_cuestionario', $cuestionarioId]));
            }
        }
    }

    private function applyFilters(Builder $query, array $filters): Builder
    {
        if (isset($filters['id_cuestionario'])) {
            $query->where('id_cuestionario', $filters['id_cuestionario']);
        }

        if (isset($filters['enunciado'])) {
            $search = '%' . strtolower($filters['enunciado']) . '%';
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(enunciado) LIKE ?', [$search]);
            });
        }

        return $query;
    }
}
