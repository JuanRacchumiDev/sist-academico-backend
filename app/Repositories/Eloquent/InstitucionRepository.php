<?php
namespace App\Repositories\Eloquent;

use App\Models\Institucion;
use App\Repositories\Contracts\IInstitucionRepository;

class InstitucionRepository implements IInstitucionRepository {
    public function findById(int $id): ?Institucion
    {
        return Institucion::findOrFail($id);
    }
}