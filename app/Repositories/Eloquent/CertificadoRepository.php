<?php

namespace App\Repositories\Eloquent;

use App\Models\Certificado;
use App\Repositories\Contracts\ICertificadoRepository;
use App\Services\Contracts\IStorageService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CertificadoRepository implements ICertificadoRepository
{
    protected IStorageService $storageService;

    public function __construct(IStorageService $storageService)
    {
        $this->storageService = $storageService;
    }

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
        ])->find($id);
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

        return DB::transaction(function () use ($certificado) {
            // Obteniendo la ruta de los archivos a eliminar
            $pdfPath = trim("{$certificado->path_file}/{$certificado->filename}");
            $qrPath = $certificado->codigo_qr_path;

            $filesToDelete = array_filter([$pdfPath, $qrPath]);

            // Eliminar archivos físicos
            if (!empty($filesToDelete)) {
                try {
                    $this->storageService->delete($filesToDelete);
                } catch (\Exception $e) {
                    Log::warning("No se pudieron eliminar algunos archivos del storage para el certificado ID {$certificado->id}: " . $e->getMessage());
                }
            }

            return (bool) $certificado->delete();
        });
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
                // Convertimos el término de búsqueda a minúsculas
                $searchTerm = '%' . mb_strtolower($search, 'UTF-8') . '%';

                // Búsqueda insensible a mayúsculas/minúsculas usando LOWER()
                $q->whereRaw('LOWER(codigo_verificacion) LIKE ?', [$searchTerm])
                    ->orWhereRaw('LOWER(nombre_impresion) LIKE ?', [$searchTerm]);
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
