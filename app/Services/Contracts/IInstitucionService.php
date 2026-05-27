<?php

namespace App\Services\Contracts;

use App\Models\Institucion;
use Illuminate\Database\Eloquent\Collection;

interface IInstitucionService
{
    public function getAllInstituciones(): Collection;

    public function getInstitucionById(int $id): ?Institucion;
}
