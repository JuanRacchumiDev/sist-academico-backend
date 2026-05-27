<?php

namespace App\Repositories\Eloquent;

use App\Models\Institucion;
use App\Repositories\Contracts\IInstitucionRepository;
use Illuminate\Database\Eloquent\Collection;
use Override;

class InstitucionRepository implements IInstitucionRepository
{
    public function getAll(): Collection
    {
        return Institucion::get();
    }

    public function findById(int $id): ?Institucion
    {
        return Institucion::findOrFail($id);
    }
}
