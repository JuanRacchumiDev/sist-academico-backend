<?php

namespace App\Repositories\Contracts;

use App\Models\Institucion;
use Illuminate\Database\Eloquent\Collection;

interface IInstitucionRepository
{
    public function getAll(): Collection;

    public function findById(int $id): ?Institucion;
}
