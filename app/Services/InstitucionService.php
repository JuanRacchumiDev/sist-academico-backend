<?php

namespace App\Services;

use App\Models\Institucion;
use App\Repositories\Contracts\IInstitucionRepository;
use App\Services\Contracts\IInstitucionService;
use Illuminate\Database\Eloquent\Collection;

class InstitucionService implements IInstitucionService
{
    protected IInstitucionRepository $institucionRepository;

    public function __construct(IInstitucionRepository $institucionRepository)
    {
        $this->institucionRepository = $institucionRepository;
    }

    public function getAllInstituciones(): Collection
    {
        return $this->institucionRepository->getAll();
    }

    public function getInstitucionById(int $id): ?Institucion
    {
        return $this->institucionRepository->findById($id);
    }
}
