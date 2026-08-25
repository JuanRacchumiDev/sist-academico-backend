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

        $certificados = $query->get();

        return $certificados;
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
            'sucursal',
            'plantilla',
            'programa',
            'modulo'
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
        $query = Certificado::query()
            ->with([
                'persona',
                'tipoCertificado',
                'sucursal',
                'plantilla',
                'programa',
                'modulo'
            ]);

        // Filtro por Búsqueda General (Código de verificación, Nombres o Documento de la Persona)
        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function (Builder $q) use ($search) {
                $searchTerm = '%' . $search . '%';
                $q->where('codigo_verificacion', 'LIKE', $searchTerm)
                    ->orWhere('nombre_impresion', 'LIKE', $searchTerm)
                    ->orWhereHas('persona', function (Builder $qPersona) use ($searchTerm) {
                        $qPersona->where('nombres', 'LIKE', $searchTerm)
                            ->orWhere('apellido_paterno', 'LIKE', $searchTerm)
                            ->orWhere('apellido_materno', 'LIKE', $searchTerm)
                            ->orWhere('nombre_completo', 'LIKE', $searchTerm)
                            ->orWhere('numero_documento', 'LIKE', $searchTerm);
                    });
            });
        }

        // Filtro por Tipo de Certificado
        if (!empty($filters['codigo_tipocertificado'])) {
            $query->where('codigo_tipocertificado', $filters['codigo_tipocertificado']);
        }

        // Filtro por Institución
        if (!empty($filters['id_sucursal'])) {
            $query->where('id_sucursal', $filters['id_sucursal']);
        }

        // Filtro por Programa
        if (!empty($filters['id_programa'])) {
            $query->where('id_programa', $filters['id_programa']);
        }

        // Filtro por Módulo
        if (!empty($filters['id_modulo'])) {
            $query->where('id_modulo', $filters['id_modulo']);
        }

        // Filtro por Rango de Fechas de Creación
        if (!empty($filters['fecha_inicio'])) {
            $query->whereDate('fecha_crea', '>=', $filters['fecha_inicio']);
        }

        if (!empty($filters['fecha_final'])) {
            $query->whereDate('fecha_crea', '<=', $filters['fecha_final']);
        }

        // Ordenamiento descendente por fecha de registro
        return $query->orderBy('id', 'DESC');
    }
}
