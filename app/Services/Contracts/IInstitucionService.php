<?php

namespace App\Services\Contracts;

use App\DTOs\Institucion\InstitucionCreateDTO;
use App\DTOs\Institucion\InstitucionUpdateDTO;
use App\Models\Institucion;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface IInstitucionService
{
    public function getAll(): Collection;

    public function getAllFiltered(array $filters): Collection;

    public function getAllFilteredPaginate(array $filters, int $perPage): LengthAwarePaginator;

    public function getInstitucionById(int $id): ?Institucion;

    public function createInstitucion(InstitucionCreateDTO $institucionCreateDTO): Institucion;

    public function updateInstitucion(int $id, InstitucionUpdateDTO $institucionUpdateDTO): ?Institucion;
}
