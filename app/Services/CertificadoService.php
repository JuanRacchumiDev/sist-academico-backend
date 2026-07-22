<?php

namespace App\Services;

use App\DTOs\Certificado\CertificadoCreateDTO;
use App\DTOs\Certificado\CertificadoUpdateDTO;
use App\Models\Certificado;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\ICertificadoRepository;
use App\Repositories\Contracts\IInstitucionRepository;
use App\Repositories\Contracts\IModuloRepository;
use App\Repositories\Contracts\IPersonaRepository;
use App\Repositories\Contracts\IPlantillaRepository;
use App\Services\Contracts\ICertificadoService;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Override;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use setasign\Fpdi\Fpdi;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;

class CertificadoService implements ICertificadoService
{
    protected ICertificadoRepository $certificadoRepository;
    protected IPersonaRepository $personaRepository;
    protected IInstitucionRepository $institucionRepository;
    protected IModuloRepository $moduloRepository;
    protected IPlantillaRepository $plantillaRepository;

    public function __construct(
        ICertificadoRepository $certificadoRepository,
        IPersonaRepository $personaRepository,
        IInstitucionRepository $institucionRepository,
        IModuloRepository $moduloRepository,
        IPlantillaRepository $plantillaRepository
    ) {
        $this->certificadoRepository = $certificadoRepository;
        $this->personaRepository = $personaRepository;
        $this->institucionRepository = $institucionRepository;
        $this->moduloRepository = $moduloRepository;
        $this->plantillaRepository = $plantillaRepository;
    }

    public function getAllCertificados(?array $searchParams = null): Collection
    {
        Log::info('Obteniendo certificados registrados', ['searchParams' => $searchParams]);

        return $this->certificadoRepository->getAll($searchParams);
    }

    public function getAllCertificadosWithFilters(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->certificadoRepository->getAllFiltered($filters, $perPage);
    }

    public function generatePDF(int $id)
    {
        $certificado = $this->certificadoRepository->findById($id);

        if (!$certificado) {
            throw new Exception("Certificado no encontrado para generar PDF");
        }

        $institucion = $certificado->institucion;
        $programa = $certificado->programa;
        $plantilla = $certificado->plantilla;

        $pdfFullPath = "{$certificado->path_file}/{$certificado->filename}";
        $qrFullPath = $certificado->codigo_qr_path;

        $qrData = route('certificados.show', [
            'id' => $certificado->id,
            'verify' => $certificado->codigo_verificacion
        ]);

        $qrRaw = QrCode::format('svg')->size(150)->margin(0)->generate($qrData);

        Storage::disk("local")->put($qrFullPath, $qrRaw);

        $logoBase64 = null;

        $logoPath = $institucion->logo_path
            ? storage_path("app/public/" . $institucion->logo_path)
            : public_path("images/logo_default.png");

        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode($logoData);
        }

        // Plantilla de Fondo (Desde el modelo Plantilla si existe)
        $templateBase64 = null;
        $imgFondo = $plantilla->path_imagen ?? 'PLANTILLA_INNOVA.jpg';
        $templatePath = storage_path("app/public/templates/{$imgFondo}");

        if (file_exists($templatePath)) {
            $templateBase64 = "data:image/jpeg;base64," . base64_encode(file_get_contents($templatePath));
        }

        $qrBase64 = "data:image/svg+xml;base64," . base64_encode($qrRaw);

        // Preparar data
        $pdfData = [
            'certificado'      => $certificado,
            'nombre_impresion' => $certificado->nombre_impresion,
            'programa'         => $programa->titulo ?? "Programa Académico",
            'fecha_inicio'     => $programa ? Carbon::parse($programa->fecha_inicio)->format('d/m/Y') : '',
            'fecha_fin'        => $programa ? Carbon::parse($programa->fecha_fin)->format('d/m/Y') : '',
            'codigo_verif'     => $certificado->codigo_verificacion,
            'qrCode'           => $qrBase64,
            'logo'             => $logoBase64,
            'fondo'            => $templateBase64,
            'fecha_emision'    => Carbon::parse($certificado->created_at)->format('d/m/Y H:i A'),
            'periodo'          => $this->getPeriodoAcademico()
        ];

        // Renderizar y guardar
        $pdf = Pdf::loadView('pdf.certificado', $pdfData)->setPaper('a4', 'landscape');

        Storage::disk("local")->put($pdfFullPath, $pdf->output());

        return Storage::disk("local")->path($pdfFullPath);
    }

    public function generateCertificadoModular(?array $searchParams): string
    {
        $persona = $this->personaRepository->findById($searchParams['id_persona']);
        $modulo = $this->moduloRepository->findById($searchParams['id_modulo']);
        $plantilla = $this->plantillaRepository->findById($searchParams['id_plantilla']);
        $codigoVerificacion = $searchParams['codigo_verificacion'];
        $programa = $modulo->programa;

        $pathLimpio = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, ltrim($plantilla->path, '/\\'));

        if (str_starts_with($pathLimpio, 'public' . DIRECTORY_SEPARATOR)) {
            $pathLimpio = substr($pathLimpio, 7);
        }

        $plantillaAbsolutePath = Storage::disk('public')->path($pathLimpio);

        if (!file_exists($plantillaAbsolutePath)) {
            throw new \Exception("No se encontró la plantilla en: {$plantillaAbsolutePath}");
        }

        $yearMonthDir = date('Y') . DIRECTORY_SEPARATOR . date('m'); // Ej: 2026/07
        Storage::disk('public')->makeDirectory($yearMonthDir);

        $urlVerificacion = config('app.url') . "/validar-certificado/" . $codigoVerificacion;
        $qrFilename = "qr_{$codigoVerificacion}.png";
        $qrRelativePath = $yearMonthDir . DIRECTORY_SEPARATOR . $qrFilename;

        $this->generateCodeQR($urlVerificacion, $qrRelativePath);
        $qrAbsolutePath = Storage::disk('public')->path($qrRelativePath);

        $pdf = new Fpdi();
        $pdf->SetAutoPageBreak(false);

        $pageCount = $pdf->setSourceFile($plantillaAbsolutePath);
        $templateId = $pdf->importPage(1);
        $size = $pdf->getTemplateSize($templateId);

        $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
        $pdf->useTemplate($templateId);

        $pdf->SetFont('Arial', 'B', 24);
        $pdf->SetTextColor(30, 41, 59);
        $nombreCompleto = utf8_decode($persona->nombre_completo ?? "{$persona->nombres} {$persona->apellido_paterno} {$persona->apellido_materno}");
        $pdf->SetXY(0, 85);
        $pdf->Cell($size['width'], 12, $nombreCompleto, 0, 1, 'C');

        $pdf->SetFont('Arial', '', 14);
        $pdf->SetTextColor(71, 85, 105);
        $pdf->SetXY(20, 105);
        $pdf->MultiCell($size['width'] - 40, 7, utf8_decode("Por haber aprobado el Módulo: {$modulo->titulo}"), 0, 'C');

        $pdf->SetFont('Arial', 'I', 11);
        $fechaInicio = $programa->fecha_inicio ? date('d/m/Y', strtotime($programa->fecha_inicio)) : '-';
        $fechaFin = $programa->fecha_final ? date('d/m/Y', strtotime($programa->fecha_final)) : '-';
        $pdf->SetXY(0, 125);
        $pdf->Cell($size['width'], 8, utf8_decode("Realizado del {$fechaInicio} al {$fechaFin}"), 0, 1, 'C');

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetXY(15, $size['height'] - 15);
        $pdf->Cell(0, 5, utf8_decode("Cód. Verificación: {$codigoVerificacion}"), 0, 0, 'L');

        $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
        $pdf->SetAutoPageBreak(true, 20);

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetTextColor(15, 23, 42);
        $pdf->SetXY(20, 20);
        $pdf->Cell(0, 10, utf8_decode("DETALLE DEL CONTENIDO ACADÉMICO"), 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(51, 65, 85);
        $pdf->Cell(0, 8, utf8_decode("Módulo: {$modulo->titulo}"), 0, 1, 'L');

        $pdf->SetDrawColor(203, 213, 225);
        $pdf->Line(20, $pdf->GetY() + 2, $size['width'] - 20, $pdf->GetY() + 2);
        $pdf->Ln(6);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 6, utf8_decode("Temario / Contenido:"), 0, 1, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(71, 85, 105);
        $pdf->MultiCell($size['width'] - 40, 6, utf8_decode($modulo->temario ?? 'Sin temario especificado.'), 0, 'L');

        if (!empty($modulo->nota)) {
            $pdf->Ln(4);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 6, utf8_decode("Nota Obtenida: {$modulo->nota}"), 0, 1, 'L');
        }

        // Insertar QR
        $qrSize = 35;
        $qrX = $size['width'] - 20 - $qrSize;
        $qrY = $size['height'] - 20 - $qrSize;

        if (file_exists($qrAbsolutePath)) {
            $pdf->Image($qrAbsolutePath, $qrX, $qrY, $qrSize, $qrSize);
        }

        $pdf->SetXY(20, $qrY + 5);
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->SetTextColor(100, 116, 139);
        $pdf->MultiCell(
            $qrX - 25,
            4,
            utf8_decode("Este documento es una representación auténtica del certificado emitido.\nPuede verificar su validez escaneando el código QR o ingresando al enlace oficial con el código de verificación:\n{$codigoVerificacion}"),
            0,
            'L'
        );

        $pdfFilename = "certificado_modulo_{$modulo->id}_{$persona->id}_" . time() . ".pdf";
        $pdfRelativePath = $yearMonthDir . DIRECTORY_SEPARATOR . $pdfFilename;
        $pdfAbsolutePath = Storage::disk('public')->path($pdfRelativePath);

        $pdf->Output('F', $pdfAbsolutePath);

        return str_replace('\\', '/', $pdfRelativePath);
    }

    public function getCertificadoById(int $id): ?Certificado
    {
        return $this->certificadoRepository->findById($id);
    }

    public function createCertificado(CertificadoCreateDTO $dto): Certificado
    {
        $data = $dto->toArray();
        $fechaActual = Carbon::now();
        $anio = $fechaActual->year;

        if (empty($data['codigo_verificacion'])) {
            $data['codigo_verificacion'] = strtoupper(Str::random(10));
        }

        $persona = $this->personaRepository->findById($data['id_persona']);
        $nombreCompleto = "{$persona->nombres} {$persona->apellido_paterno} {$persona->apellido_materno}";

        $institucion = $this->institucionRepository->findById($data['id_institucion']);
        $nombreInsSlug = Str::slug($institucion->nombre);
        $dni = $persona->numero_documento;

        $folderPath = "certificados/{$anio}/{$nombreInsSlug}/{$dni}";
        $fileName = "CERT-{$data['codigo_verificacion']}.pdf";

        $data['nombre_impresion'] = $nombreCompleto;
        $data['path_file'] = $folderPath;
        $data['filename'] = $fileName;
        $data['codigo_qr_path'] = "{$folderPath}/QR_{$data['codigo_verificacion']}.svg";

        return $this->certificadoRepository->create($data);
    }

    public function updateCertificado(int $id, CertificadoUpdateDTO $dto): ?Certificado
    {
        $certificado = $this->certificadoRepository->findById($id);

        if (!$certificado) return null;

        $data = array_filter($dto->toArray(), fn($value) => !is_null($value));

        return $this->certificadoRepository->update($id, $data);
    }

    public function deleteCertificado(int $id): bool
    {
        $certificado = $this->certificadoRepository->findById($id);

        if (!$certificado) return false;

        return $this->certificadoRepository->delete($id);
    }

    private function getPeriodoAcademico(): string
    {
        $mes = now()->month;
        return now()->year . ($mes <= 7 ? ' - I' : ' - II');
    }

    private function generateCodeQR(string $url, string $codigo): string
    {
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($url)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(300)
            ->margin(10)
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->build();

        $qrPath = "qrs/{$codigo}.png";
        Storage::disk('local')->put($qrPath, $result->getString());

        return $qrPath;
    }
}
