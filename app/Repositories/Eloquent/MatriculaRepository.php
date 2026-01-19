<?php
namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\IPersonaRepository;
use App\Repositories\Contracts\IDetalleParametroRepository;
use App\DTOs\Matricula\MatriculaCreateDTO;
use App\Models\Matricula;
use App\Models\DetalleMatricula;
use App\Models\PersonaPrograma;
use App\Models\Programa;
use App\Models\Pago;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\IMatriculaRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;

use function PHPSTORM_META\map;

class MatriculaRepository implements IMatriculaRepository {
    protected IPersonaRepository $personaRepository;
    protected IDetalleParametroRepository $detalleRepository;

    protected string $disk = "public";
    protected string $path = "certificados/";

    public function __construct(
        IPersonaRepository $personaRepository,
        IDetalleParametroRepository $detalleRepository
    ) {
        $this->personaRepository = $personaRepository;
        $this->detalleRepository = $detalleRepository;
    }

    public function getAll(?array $searchParams = null): Collection
    {
        $query = Matricula::with([
            'alumno',
            'sede',
            'detalles',
            'estadoMatricula'
        ]);

        if ($searchParams) {
            $query->where(function($q) use ($searchParams) {
                if (isset($searchParams['search'])) {
                    $search = '%'.strtolower($searchParams['search']).'%';

                    $q->whereRaw('LOWER(nombre_alumno) LIKE ?', [$search])
                        ->orWhereRaw('LOWER(nombre_sede) LIKE ?', [$search])
                        ->orWhereRaw('LOWER(nombre_programa) LIKE ?', [$search]);
                }
            });
        }

        return $query->get();
    }

    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = Matricula::with([
            'alumno',
            'sede',
            'detalles',
            'estadoMatricula'
        ]);

        if (isset($filters['estado'])) {
            $query->where('estado', (bool)$filters['estado']);
        }

        // Aplicar búsqueda por texto
        if (isset($filters['search'])) {
            $search = '%'.strtolower($filters['search']).'%';

            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nombre_alumno) LIKE ?', [$search])
                    ->orWhereRaw('LOWER(nombre_sede) LIKE ?', [$search])
                    ->orWhereRaw('LOWER(nombre_programa) LIKE ?', [$search]);
            });
        }

        $query->orderBy('id', 'desc');

        return $query->paginate($perPage);
    }

    public function getUniqueForFilters(array $filters): ?Matricula
    {
        // throw ValidationException::withMessages(['filters' => $filters]);

        return Matricula::with([
            'alumno',
            'sede',
            'detalles',
            'estadoMatricula'
        ])->find($filters['id_matricula']);
    }

    public function getFilePath(array $filters): string
    {
        $matriculaId = $filters['id_matricula'];
        $programaId = $filters['id_programa'];
        $alumnoId = $filters['id_alumno'];

        $mIdPadded = sprintf('%04d', $matriculaId);
        $pIdPadded = sprintf('%04d', $programaId);
        $aIdPadded = sprintf('%04d', $alumnoId);

        Log::info('Validate path certificado pdf', ['mIdPadded' => $mIdPadded]);
        Log::info('Validate path certificado pdf', ['pIdPadded' => $pIdPadded]);
        Log::info('Validate path certificado pdf', ['aIdPadded' => $aIdPadded]);

        $pathPDF = $this->path . "certificado_{$mIdPadded}_{$pIdPadded}_{$aIdPadded}.pdf"; 
        Log::info('Validate path certificado pdf', ['pathPDF' => $pathPDF]);

        return $pathPDF;
    }

    public function existsCertificado(array $filters): bool
    {
        $filePath = $this->getFilePath($filters);
        return Storage::disk($this->disk)->exists($filePath);
    }

    public function savePDF(array $filters, string $pdfContent): void
    {
        $filePath = $this->getFilePath($filters);
        Storage::disk($this->disk)->put($filePath, $pdfContent);
    }

    public function getPDF(array $filters): string
    {
        $filePath = $this->getFilePath($filters);
        return Storage::disk($this->disk)->get($filePath);
    }

    public function getCertificadoData(array $filters): ?array
    {
        Log::info('Validate getCertificadoData', ['filters' => $filters]);

        $idMatricula = $filters['id_matricula'];
        $idPrograma = $filters['id_programa'];
        $idAlumno = $filters['id_alumno'];

        $result = DB::table('detalle_matricula', 'dmat')
            ->select([
                'persona.numero_documento',
                'persona.apellido_paterno',
                'persona.apellido_materno',
                'persona.nombre_completo',
                'persona.email',
                'persona.telefono',
                'programa.nombre as nombre_programa',
                'programa.fecha_inicio',
                'programa.fecha_final',
                'programa.duracion',
                'programa.horas_academicas',
                'programa.modulos',
                'programa.creditos',
                'programa.modalidad',
                'matricula.fecha_matricula'
            ])
            ->join('persona', 'persona.id', '=', 'dmat.id_alumno')
            ->join('programa', 'programa.id', '=', 'dmat.id_programa')
            ->join('matricula', 'matricula.id', '=', 'dmat.id_matricula')
            ->where('dmat.id_matricula', $idMatricula)
            ->where('dmat.id_programa', $idPrograma)
            ->where('dmat.id_alumno', $idAlumno)
            ->first();

        Log::info('Validate result', ['result' => $result]);

        return $result ? (array) $result : null;
    }

    public function findById(int $id): ?Matricula
    {
        return Matricula::with([
            'alumno',
            'sede',
            'detalles',
            'estadoMatricula'
        ])->find($id);
    }

    /**
     * @throws Throwable
     */
    public function create(MatriculaCreateDTO $dto): Matricula
    {
        return DB::transaction(function () use ($dto){
            $fechaActual = date('Y-m-d');
            Log::info('fechaActual', ['fechaActual' => $fechaActual]);

            $matriculaData = $dto->except('programas')->toArray();
            
            $matriculaData = array_filter($matriculaData, fn($value) => !is_null($value));
            Log::debug("MatriculaRepository: matriculaData", ['matricula_data' => $matriculaData]);

            $idAlumno = $matriculaData['id_alumno'];

            if (isset($idAlumno)) {
                $alumno = $this->personaRepository->findById($idAlumno);
                $nombre = $alumno->nombres." ".$alumno->apellido_paterno." ".$alumno->apellido_materno;
                $matriculaData['nombre_alumno'] = $nombre;
            }

            Log::debug("MatriculaRepository: matriculaData with nombre_alumno", ['matricula_data' => $matriculaData]);

            if (isset($matriculaData['id_sede'])) {
                $idSede = $matriculaData['id_sede'];
                $sede = $this->detalleRepository->findByCodigo($idSede);
                $matriculaData['nombre_sede'] = $sede->nombre;
            }

            Log::debug("MatriculaRepository: matriculaData with nombre_sede", ['matricula_data' => $matriculaData]);

            if (isset($matriculaData['id_formapago'])) {
                $idFormaPago = $matriculaData['id_formapago'];
                $formaPago = $this->detalleRepository->findByCodigo($idFormaPago);
                $matriculaData['nombre_formapago'] = $formaPago->nombre;
            }

            Log::debug("MatriculaRepository: matriculaData with nombre_formapago", ['matricula_data' => $matriculaData]);

            if (isset($matriculaData['id_estadopago'])) {
                $idEstadoPago = $matriculaData['id_estadopago'];
                $estadoPago = $this->detalleRepository->findByCodigo($idEstadoPago);
                $matriculaData['nombre_estadopago'] = $estadoPago->nombre;
            }

            Log::debug("MatriculaRepository: matriculaData with nombre_estadopago", ['matricula_data' => $matriculaData]);

            if (isset($matriculaData['id_metodopago'])) {
                $idMetodoPago = $matriculaData['id_metodopago'];
                $metodoPago = $this->detalleRepository->findByCodigo($idMetodoPago);
                $matriculaData['nombre_metodopago'] = $metodoPago->nombre;
            }

            Log::debug("MatriculaRepository: matriculaData with nombre_metodopago", ['matricula_data' => $matriculaData]);

            if (isset($matriculaData['id_estadomatricula'])) {
                $idEstadoMatricula = $matriculaData['id_estadomatricula'];
                $estadoMatricula = $this->detalleRepository->findByCodigo($idEstadoMatricula);
                $matriculaData['nombre_estadomatricula'] = $estadoMatricula->nombre;
            }

            Log::debug("MatriculaRepository: matriculaData with nombre_estadomatricula", ['matricula_data' => $matriculaData]);
            
            // $payloadMatricula = Arr::except($matriculaData, ['id_metodopago']);

            // Log::debug("MatriculaRepository: matriculaData without id_metodopago", ['matricula_data' => $matriculaData]);

            Log::debug('Test MatriculaData', ['matricula_data' => $matriculaData]);

            $matricula = Matricula::create($matriculaData);
            // $matricula = Matricula::create($payloadMatricula);

            Log::debug("MatriculaRepository: matricula", ['matricula_data' => $matricula]);
            
            $detalleMatriculaData = [];
            // $personaProgramaData = [];

            $programas = Programa::whereIn('id', $dto->programas)
                ->pluck('nombre', 'id');

            Log::debug("MatriculaRepository: programas", ['programas_data' => $programas]);

            foreach ($dto->programas as $programaId) {
                $nombrePrograma = $programas->get($programaId) ?? "Nombre no encontrado";

                $detalleMatriculaData[] = [
                    'id_matricula' => $matricula->id,
                    'id_programa' => $programaId,
                    // 'id_alumno' => $idAlumno,
                    'nombre_programa' => $nombrePrograma,
                    // 'nombre_alumno' => (isset($matriculaData['nombre_alumno'])) ? $matriculaData['nombre_alumno'] : null,
                    'estado' => $dto->estado
                ];

                // $personaProgramaData[] = [
                //     'id_persona' => $dto->id_alumno,
                //     'id_programa' => $programaId
                // ];
            }

            $pagoData = [
                'id_matricula' => $matricula->id,
                'id_alumno' => $matriculaData['id_alumno'],
                'id_formapago' => $matriculaData['id_formapago'],
                'id_metodopago' => $matriculaData['id_metodopago'],
                'id_estadopago' => $matriculaData['id_estadopago'],
                'concepto' => 'PAGO DE MATRÍCULA',
                'fecha_pago' => $fechaActual,
                // 'nro_operacion' => $matriculaData['numero_operacion'],
                'monto_total' => $matriculaData['monto'],
                'monto_pagado' => $matriculaData['monto'],
                'estado' => true
            ];

            if (isset($matriculaData['numero_operacion'])) {
                Log::debug('Validate numero_operacion', ['código test' => 1]);
                $pagoData['nro_operacion'] = $matriculaData['numero_operacion'];
            } else {
                Log::debug('Validate numero_operacion', ['código test' => 2]);
            }

            Log::debug("MatriculaRepository: pagoData", ['pago_data' => $pagoData]);

            if (!empty($detalleMatriculaData)) {
                DetalleMatricula::insert($detalleMatriculaData);
            }

            // if (!empty($personaProgramaData)) {
            //     PersonaPrograma::insert($personaProgramaData);
            // }

            if (!empty($pagoData)) {
                Pago::insert($pagoData);
            }

            return $matricula;
        });
    }

    public function update(int $id, array $data): ?Matricula
    {
        $matricula = $this->findById($id);

        if ($matricula) {
            $matricula->update($data);
            return $matricula;
        }

        return null;
    }

    public function delete(int $id): bool
    {
        $matricula = $this->findById($id);

        if ($matricula) {
            return $matricula->delete();
        }

        return false;
    }
}