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
use Dompdf\Dompdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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

    public function getCertificadoPDF(array $filters): array
    {
        // Verificar si el certificado ya existe
        if ($this->matriculaRepository->existsCertificado($filters)) {
            $pdfContent = $this->matriculaRepository->getPDF($filters);
            return [
                'pdfContent' => $pdfContent,
                'status' => 'success',
                'message' => 'Certificado obtenido de la caché de archivos'
            ];
        }

        // Si no existe, obtener datos y generar
        $matricula = $this->matriculaRepository->getCertificadoData($filters);

        if (!$matricula) {
            return [
                'pdfContent' => null,
                'status' => 'error',
                'message' => 'Matrícula no encontrada'
            ];
        }

        $matriculaId = $filters['id_matricula'];
        $programaId = $filters['id_programa'];
        $alumnoId = $filters['id_alumno'];

        // Generar el código QR de validación
        // Esta URL debe apuntar a un endpoint público para validar el certificado
        $validationUrl = url("/public/certificados/validar/matricula/{$matriculaId}/programa/{$programaId}/alumno/{$alumnoId}");

        $qrCodeSvg = QrCode::size(100)
            ->color(0, 0, 0)
            ->generate($validationUrl);

        $html = view('pdf.certificado', [
            'title' => 'CONSTANCIA DE ESTUDIOS',
            'matricula' => $matricula,
            'qrCodeSvg' => $qrCodeSvg,
            'validationUrl' => $validationUrl
        ])->render();

        $domPDF = new Dompdf();
        $domPDF->loadHtml($html);
        $domPDF->setPaper('A4', 'portrait');
        $domPDF->render();
        $pdfContent = $domPDF->output();

        // Guardar el certificado generado para futuras solicitudes
        $this->matriculaRepository->savePDF($filters, $pdfContent);

        return [
            'pdfContent' => $pdfContent,
            'status' => 'success',
            'message' => 'Certificado generado y almacenado'
        ];
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