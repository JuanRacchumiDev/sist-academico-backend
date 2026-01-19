<?php
namespace App\Repositories\Eloquent;

use App\Models\Pago;
use App\Repositories\Contracts\IPagoRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PagoRepository implements IPagoRepository {
    public function getAll(?array $searchParams = null): Collection
    {
        $query = Pago::with([
            'matricula',
            'programa',
            'alumno',
            'formaPago',
            'metodoPago',
            'estadoPago'
        ]);

        if ($searchParams) {
            $query->where(function($q) use ($searchParams) {
                if (isset($searchParams['search'])) {
                    $search = '%'.strtolower($searchParams['search']).'%';

                    $q->whereRaw('LOWER(concepto) LIKE ?', [$search]);
                }
            });
        }

        return $query->get();
    }

    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = Pago::with([
            'matricula',
            'programa',
            'alumno',
            'formaPago',
            'metodoPago',
            'estadoPago'
        ]);

        if (isset($filters['estado'])) {
            $query->where('estado', (bool)$filters['estado']);
        }

        // Aplicar búsqueda por texto
        if (isset($filters['search'])) {
            $search = '%'.strtolower($filters['search']).'%';

            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(concepto) LIKE ?', [$search]);
            });
        }

        $query->orderBy('id', 'desc');

        return $query->paginate($perPage);
    }

    public function findById(int $id): ?Pago
    {
        return Pago::with([
            'matricula',
            'programa',
            'alumno',
            'formaPago',
            'metodoPago',
            'estadoPago'
        ])->find($id);
    }

    public function create(array $data): Pago
    {
        return Pago::create($data);
    }

    public function update(int $id, array $data): ?Pago
    {
        $pago = $this->findById($id);

        if ($pago) {
            $pago->update($data);
            return $pago;
        }

        return null;
    }

    public function delete(int $id): bool
    {
        $pago = $this->findById($id);

        if ($pago) {
            return $pago->delete();
        }

        return false;   
    }
}