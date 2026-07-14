<?php

namespace App\Repositories\Contracts;

use App\Models\Pregunta;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IPreguntaRepository
{
    public function getAll(?array $searchParams = null): Collection;
    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator;
    public function findById(int $id): ?Pregunta;
    public function create(array $data): Pregunta;
    public function update(int $id, array $data): ?Pregunta;
    public function delete(int $id): bool;

    public function createMany(int $cuestionarioId, array $preguntasData): void;
    public function syncPreguntas(int $cuestionarioId, array $preguntasData): void;
}
