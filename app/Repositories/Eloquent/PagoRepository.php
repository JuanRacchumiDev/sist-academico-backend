<?php
namespace App\Repositories\Eloquent;

use App\Models\Pago;
use App\Repositories\Contracts\IPagoRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PagoRepository implements IPagoRepository {

    protected string $disk = "public";
    protected string $path = "pdfs/matriculas/";

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

    public function getMatriculaData(array $filters)
    {
        Log::info('Validate paremeters filters getMatricula', ['filters' => $filters]);

        $idAlumno = $filters['id_alumno'];
        $idMatricula = $filters['id_matricula'];

        Log::info('idAlumno getMatricula', ['idAlumno' => $idAlumno]);
        Log::info('idMatricula getMatricula', ['idMatricula' => $idMatricula]);

        $pagoData = DB::table('pago as p')
            ->join('matricula as m', 'm.id', '=', 'p.id_matricula')
            ->join('persona as p2', 'p2.id', '=', 'p.id_alumno')
            ->join('detalle_parametro as dp', 'dp.codigo', '=', 'p.id_formapago')
            ->join('detalle_parametro as dp2', 'dp2.codigo', '=', 'p.id_metodopago')
            ->join('detalle_parametro as dp3', 'dp3.codigo', '=', 'p.id_estadopago')
            ->select(
                'm.fecha_matricula',
                'p2.nombres',
                'p2.apellido_paterno',
                'p2.apellido_materno',
                'p2.nombre_completo',
                'p2.id_tipodocumento',
                'p2.numero_documento',
                'dp.nombre as nombre_formapago',
                'dp2.nombre as nombre_metodopago',
                'dp3.nombre as nombre_estadopago',
                'p.*'
            )
            ->where('p.concepto', 'like', '%PAGO%MATRÍCULA%')
            ->whereNull('p.id_programa')
            ->where('p.id_alumno', $idAlumno)
            ->where('p.id_matricula', $idMatricula)
            ->first();

        Log::info('pagoData getMatriculaData', ['pagoData' => $pagoData]);

        return $pagoData;
    }

    public function getFilePath(array $filters): string
    {
        $mId = sprintf('%05d', $filters['id_matricula']);
        $aId = sprintf('%05d', $filters['id_alumno']);
        return "{$this->path}recibo_matricula_{$mId}_alumno_{$aId}.pdf";
    }

    public function existsMatricula(array $filters): bool
    {
        return Storage::disk($this->disk)->exists($this->getFilePath($filters));
    }

    public function savePDF(array $filters, string $pdfContent): void
    {
        Storage::disk($this->disk)->put($this->getFilePath($filters), $pdfContent);
    }

    public function getPDF(array $filters): string
    {
        return Storage::disk($this->disk)->get($this->getFilePath($filters));
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