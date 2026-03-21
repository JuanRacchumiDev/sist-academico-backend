<?php
namespace App\Repositories\Contracts;

use App\Models\Institucion;

interface IInstitucionRepository 
{
    public function findById(int $id): ?Institucion;
}