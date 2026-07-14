<?php

namespace App\Services\Contracts;

use App\DTOs\Cuestionario\CuestionarioCreateDTO;
use App\DTOs\Cuestionario\CuestionarioUpdateDTO;
use App\Models\Cuestionario;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ICuestionarioService
{
    public function getAllCuestionarios(?array $searchParams = null): Collection;
    public function getAllCuestionariosWithFilters(array $filters, int $perPage): LengthAwarePaginator;
    public function getCuestionarioById(int $id): ?Cuestionario;
    public function createCuestionario(CuestionarioCreateDTO $dto): Cuestionario;
    public function updateCuestionario(int $id, CuestionarioUpdateDTO $dto): ?Cuestionario;
}
