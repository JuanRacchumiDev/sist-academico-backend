<?php
namespace App\Services;

use App\DTOs\Matricula\MatriculaCreateDTO;
use App\Models\Matricula;
use App\Repositories\Contracts\IDetalleParametroRepository;
use App\Repositories\Contracts\IMatriculaRepository;
use App\Repositories\Contracts\IPagoRepository;
use App\Repositories\Contracts\IPersonaRepository;
use App\Repositories\Contracts\IProgramaRepository;
use App\Repositories\Eloquent\DetalleParametroRepository;
use App\Repositories\Eloquent\PagoRepository;
use App\Repositories\Eloquent\PersonaRepository;
use App\Repositories\Eloquent\ProgramaRepository;
use App\Services\Contracts\IMatriculaService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;

class MatriculaService implements IMatriculaService {
    protected IMatriculaRepository $matriculaRepository;
    protected IPersonaRepository $personaRepository;
    protected IDetalleParametroRepository $detalleRepository;
    protected IProgramaRepository $programaRepository;
    protected IPagoRepository $pagoRepository;

    public function __construct(
        IMatriculaRepository $matriculaRepository,
        PersonaRepository $personaRepository,
        DetalleParametroRepository $detalleRepository,
        ProgramaRepository $programaRepository,
        PagoRepository $pagoRepository
        )
    {
        $this->matriculaRepository = $matriculaRepository;
        $this->personaRepository = $personaRepository;
        $this->detalleRepository = $detalleRepository;
        $this->programaRepository = $programaRepository;
        $this->pagoRepository = $pagoRepository;
    }

    public function getAllMatriculas(?array $searchParams = null): Collection
    {
        return $this->matriculaRepository->getAll($searchParams);
    }

    public function getAllMatriculasWithFilters(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->matriculaRepository->getAllFiltered($filters, $perPage);
    }

    public function getFichaByFilters(array $filters)
    {
        // Obtener datos de la matrícula
        $matricula = $this->matriculaRepository->getUniqueForFilters($filters);

        $empresa = [
            'razon_social' => 'COOPERATIVA DE SERVICIOS EDUCACIONALES CAPACITA',
            'ruc' => '20603337337'
        ];

        $pago = [
            'matricula' => '120.00',
            'cuotas' => '12',
            'monto_cuota' => '120.00',
            'total' => '1560.00'
        ];

        $dataPDF = [
            'title' => 'FICHA DE MATRÍCULA',
            'content' => [
                'matricula' => $matricula,
                'empresa' => $empresa,
                'pago' => $pago
            ]
        ];

        $pdf = Pdf::loadView('pdf.ficha', $dataPDF);

        $filename = 'matricula_0001.pdf';

        return $pdf->download($filename);
    }

    public function getMatriculaById(int $id): ?Matricula
    {
        return $this->matriculaRepository->findById($id);
    }

    public function createMatricula(MatriculaCreateDTO $matriculaCreateDTO): Matricula|null
    {
        // throw ValidationException::withMessages(['matriculaCreateDTO' => $matriculaCreateDTO]);
        return $this->matriculaRepository->create($matriculaCreateDTO);
    }

    public function deleteMatricula(int $id): bool
    {
        $matricula = $this->matriculaRepository->findById($id);

        if (!$matricula) {
            return false;
        }

        return $this->matriculaRepository->delete($id);
    }
}