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

        $matriculas = $query->get();

        return $this->filtrarCertificadosPorPersona($matriculas);
    }

    public function getAllFiltered(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        // return $this->applyFilters($filters)->paginate($perPage);
        $paginator = $this->applyFilters($filters)->paginate($perPage);

        // Modificamos directamente la colección interna del paginador
        $paginator->getCollection()->transform(function ($matricula) {
            $idPersona = $matricula->id_persona;

            foreach ($matricula->detalles as $detalle) {
                if ($detalle->programa) {
                    foreach ($detalle->programa->detalleModulos as $modulo) {
                        // Mantenemos solo los certificados que pertenecen a esta persona
                        $certificadosFiltrados = $modulo->certificados->filter(function ($cert) use ($idPersona) {
                            return $cert->id_persona == $idPersona;
                        })->values();

                        $modulo->setRelation('certificados', $certificadosFiltrados);
                    }
                }
            }

            return $matricula;
        });

        return $paginator;
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
                    // Obtenemos los módulos activos del programa
                    $q->where('estado', true)->orderBy('orden', 'ASC');
                },
                // Cargar los certificados de cada módulo pertenecientes a la persona matriculada
                'detalles.programa.detalleModulos.certificados' => function ($q) {
                    $q->where('estado', true)
                        ->with(['tipoCertificado', 'plantilla']);
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

    /**
     * Filtra en memoria para que cada módulo solo tenga los certificados 
     * que pertenecen al alumno (id_persona) de su matrícula correspondiente.
     */
    private function filtrarCertificadosPorPersona(Collection $matriculas): Collection
    {
        $matriculas->transform(function ($matricula) {
            $idPersona = $matricula->id_persona;

            foreach ($matricula->detalles as $detalle) {
                if ($detalle->programa) {
                    foreach ($detalle->programa->detalleModulos as $modulo) {
                        // Mantenemos solo los certificados que coincidan con la persona matriculada
                        $certificadosFiltrados = $modulo->certificados->filter(function ($cert) use ($idPersona) {
                            return $cert->id_persona == $idPersona;
                        })->values(); // values() reindexa el array para que JSON lo formatee como array [] y no como objeto {}

                        // Reasignamos la relación limpia
                        $modulo->setRelation('certificados', $certificadosFiltrados);
                    }
                }
            }

            return $matricula;
        });

        return $matriculas;
    }
}
