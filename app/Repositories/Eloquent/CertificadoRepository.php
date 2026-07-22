<?php

namespace App\Repositories\Eloquent;

use App\DTOs\Certificado\CertificadoCreateDTO;
use App\DTOs\Certificado\CertificadoUpdateDTO;
use App\Models\Certificado;
use App\Models\Matricula;
use App\Repositories\Contracts\ICertificadoRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class CertificadoRepository implements ICertificadoRepository
{
    public function getAll(?array $searchParams = null): Collection
    {
        $query = $this->applyFilters($searchParams ?? []);

        return $query->get();
    }

    public function getAllFiltered(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return $this->applyFilters($filters)->paginate($perPage);
    }

    public function findById(int $id): ?Certificado
    {
        return Certificado::with([
            'persona',
            'tipoCertificado',
            'plantilla',
            'programa'
        ])->findOrFail($id);
    }

    public function create(array $data): Certificado
    {
        return Certificado::create($data);
    }

    public function update(int $id, array $data): ?Certificado
    {
        $certificado = $this->findById($id);

        if (!$certificado) {
            return null;
        }

        $certificado->update($data);

        return $certificado;
    }

    public function delete(int $id): bool
    {
        $certificado = $this->findById($id);

        if (!$certificado) return false;

        Storage::disk('local')->delete(
            [
                $certificado->path_file . '/' . $certificado->filename,
                $certificado->codigo_qr_path
            ]
        );

        return $certificado->delete();
    }

    private function applyFilters(array $filters): Builder
    {
        $query = Matricula::query()
            ->where('estado', true)
            ->with([
                'persona',
                'estadoMatricula',
                'institucion',
                'detalles.programa.tipoPrograma',
                'detalles.programa.categoriaPrograma',
                'detalles.programa.detalleModulos' => function ($q) {
                    $q->where('estado', true)->orderBy('orden', 'ASC');
                },
                'pagos' => function ($q) {
                    $q->where('estado', true);
                },
                'pagoMatricula',
                'pagoModulos',
            ]);

        // Filtro por búsqueda textual (nombre, apellidos o documento de persona)
        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->whereHas('persona', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $searchTerm = '%' . $search . '%';
                    $sub->where('nombres', 'LIKE', $searchTerm)
                        ->orWhere('apellidos', 'LIKE', $searchTerm)
                        ->orWhere('numero_documento', 'LIKE', $searchTerm);
                });
            });
        }

        // Filtro por rango de fechas
        if (!empty($filters['fecha_inicio']) || !empty($filters['fecha_final'])) {
            $query->whereHas('detalles.programa', function ($q) use ($filters) {
                if (!empty($filters['fecha_inicio'])) {
                    $q->where('fecha_inicio', '>=', $filters['fecha_inicio']);
                }
                if (!empty($filters['fecha_final'])) {
                    $q->where('fecha_final', '<=', $filters['fecha_final']);
                }
            });
        }

        return $query->orderBy('created_at', 'DESC');
    }
}
