<?php
namespace App\Repositories\Eloquent;

use App\Models\Plantilla;
use App\Repositories\Contracts\IPlantillaRepository;
use Illuminate\Database\Eloquent\Collection;

class PlantillaRepository implements IPlantillaRepository {

    public function getAll(?array $searchParams = null): Collection
    {
        $query = Plantilla::query();

        if ($searchParams) {
            $query->where(function($q) use ($searchParams) {
                if (isset($searchParams['search'])) {
                    $search = '%'.strtolower($searchParams['search']).'%';
                
                    $q->whereRaw('LOWER(nombre) LIKE ?', [$search]);
                }
            });
        }

        return $query->get();
    }

    public function findById(int $id): ?Plantilla
    {
        return Plantilla::find($id);
    }

    public function create(array $data): Plantilla
    {
        $plantilla = Plantilla::create($data);
        return $plantilla;
    }

    public function update(int $id, array $data): ?Plantilla
    {
        $plantilla = $this->findById($id);

        if ($plantilla) {
            $plantilla->update($data);
            return $plantilla;
        }

        return null;
    }

    public function delete(int $id): bool
    {
        $plantilla = $this->findById($id);

        if ($plantilla) {
            return $plantilla->delete();
        }

        return false;
    }
}