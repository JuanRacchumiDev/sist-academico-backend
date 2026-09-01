<?php

namespace App\Repositories\Eloquent;

use App\Models\Pago;
use App\Repositories\Contracts\IPagoRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Override;

class PagoRepository implements IPagoRepository
{
    protected string $disk = "public";
    protected string $pathMatricula = "pdfs/matriculas/";
    protected string $pathModulo = "pdfs/pago-modulo/";
    protected string $pathOtros = "pdfs/otros/";

    public function getAll(?array $filters = null): Collection
    {
        return $this->filter($filters)->get();
    }

    public function getAllFiltered(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return $this->filter($filters)
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    public function getMatriculaData(array $filters)
    {
        Log::info('Validate paremeters filters getMatricula', ['filters' => $filters]);

        $idMatricula = $filters['id_matricula'];

        Log::info('idMatricula getMatricula', ['idMatricula' => $idMatricula]);

        $pagoData = DB::table('pago as p')
            ->join('matricula as m', 'm.id', '=', 'p.id_matricula')
            ->join('detalle_parametro as dp', 'dp.codigo', '=', 'p.codigo_formapago')
            ->join('detalle_parametro as dp3', 'dp3.codigo', '=', 'p.codigo_estadopago')
            ->select(
                'm.fecha_matricula',
                'dp.nombre as nombre_formapago',
                'dp3.nombre as nombre_estadopago',
                'p.*'
            )
            ->where('p.concepto', 'like', '%PAGO%MATRÍCULA%')
            ->whereNull('p.id_programa')
            ->where('p.id_matricula', $idMatricula)
            ->first();

        Log::info('pagoData getMatriculaData', ['pagoData' => $pagoData]);

        return $pagoData;
    }

    public function getFilePath(array $filters): string
    {
        $mId = sprintf('%05d', $filters['id_matricula']);

        // Caso Pago de módulo
        if (!empty($filters['tipo']) && $filters['tipo'] === 'pago-modulo') {
            $modNo = sprintf('%02d', $filters['numero_modulo']);
            return "{$this->pathModulo}recibo_modulo_{$modNo}_mat_{$mId}.pdf";
        }

        // Caso Pago de matrícula
        if (!empty($filters['tipo']) && $filters['tipo'] === 'matricula') {
            return "{$this->pathMatricula}recibo_matricula_{$mId}.pdf";
        }

        // Caso Otros conceptos
        return "{$this->pathOtros}recibo_pago_mat_{$mId}.pdf";
    }

    public function getPDF(array $filters): string
    {
        return Storage::disk($this->disk)->get($this->getFilePath($filters));
    }

    public function getPagoModuloData(array $filters)
    {
        Log::info('Validate paremeters filters getPagoModuloData', ['filters' => $filters]);

        $idMatricula = $filters['id_matricula'];

        Log::info('idMatricula getPagoModuloData', ['idMatricula' => $idMatricula]);

        $pagoData = DB::table('pago as p')
            ->join('programa as p2', 'p.id_programa', '=', 'p2.id')
            ->join('detalle_parametro as dp', 'dp.codigo', '=', 'p.codigo_formapago')
            ->join('detalle_parametro as dp3', 'dp3.codigo', '=', 'p.codigo_estadopago')
            ->select(
                'p2.nombre as nombre_programa',
                'dp.nombre as nombre_formapago',
                'dp3.nombre as nombre_estadopago',
                'p.*'
            )
            ->whereNotNull('p.id_programa')
            ->where('p.id_matricula', $idMatricula)
            ->first();

        Log::info('pagoData getPagoModuloData', ['pagoData' => $pagoData]);

        return $pagoData;
    }

    public function getModulosPorPagar(int $idMatricula, int $totalModulos)
    {
        $modulosData = DB::select(
            "
            SELECT 
                serie.modulo AS numero_modulo,
                false AS pagado,
                p.id AS id_pago
            FROM public.matricula m
            CROSS JOIN LATERAL generate_series(1, m.numero_modulos) AS serie(modulo)
            LEFT JOIN public.pago p ON p.id_matricula = m.id 
                AND p.numero_modulo = serie.modulo
                AND p.estado = true
            WHERE m.id = ?         
            AND m.estado = true    
            AND p.id IS NULL       
            ORDER BY serie.modulo ASC
            ",
            [$idMatricula]
        );

        return $modulosData;
    }

    public function getModulosPagados(int $idMatricula)
    {
        $modulosData = DB::select(
            "
            SELECT
                pg.id, pg.codigo_formapago, pg.numero_modulo,
                pg.concepto, pg.numero_operacion, pg.fecha_pago,
                pg.cantidad_efectivo, pg.cantidad_operacion,
                dp.nombre as nombre_formapago
            FROM
                pago pg INNER JOIN detalle_parametro dp
                ON dp.codigo = pg.codigo_formapago
            WHERE pg.id_matricula = ? AND pg.numero_modulo IS NOT NULL
            ORDER BY pg.numero_modulo ASC
            ",
            [$idMatricula]
        );

        return $modulosData;
    }

    public function getPagosByMatricula(int $idMatricula)
    {
        return Pago::with(['formaPago', 'estadoPago'])
            ->where('id_matricula', $idMatricula)
            ->where('estado', true)
            ->orderBy('numero_modulo', 'ASC')
            ->get();
    }

    public function existsPDF(array $filters): bool
    {
        return Storage::disk($this->disk)->exists($this->getFilePath($filters));
    }

    public function savePDF(array $filters, string $pdfContent): void
    {
        Storage::disk($this->disk)->put($this->getFilePath($filters), $pdfContent);
    }

    public function findById(int $id): ?Pago
    {
        return Pago::with([
            'matricula.persona',
            'estadoPago',
            'formaPago',
            'institucion'
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

    /**
     * Aplica los filtros dinámicos a la consulta del modelo Pago.
     *
     * @param array|null $filters
     * @return Builder
     */
    private function filter(?array $filters = null): Builder
    {
        $query = Pago::with([
            'matricula.persona',
            'estadoPago',
            'formaPago',
            'institucion'
        ]);

        if (empty($filters)) {
            return $query;
        }

        // 1. Filtro por estado del pago
        if (!empty($filters['estado'])) {
            $query->where('estado', (bool)$filters['estado']);
        }

        // 2. Filtro por rango de fechas de pago
        if (!empty($filters['fecha_inicio'])) {
            $query->whereDate('fecha_pago', '>=', $filters['fecha_inicio']);
        }

        if (!empty($filters['fecha_final'])) {
            $query->whereDate('fecha_pago', '<=', $filters['fecha_final']);
        }

        // 3. Filtro por Nombre Completo de la Persona (Relación Pago -> Matricula -> Persona)
        if (!empty($filters['search'])) {
            $search = '%' . strtolower($filters['search']) . '%';

            $query->whereHas('matricula.persona', function (Builder $qPersona) use ($search) {
                $qPersona->whereRaw('LOWER(nombre_completo) LIKE ?', [$search]);
            });
        }

        return $query;
    }
}
