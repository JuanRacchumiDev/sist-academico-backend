<?php

namespace App\Services;

use App\DTOs\Certificado\CertificadoCreateDTO;
use App\DTOs\Certificado\CertificadoUpdateDTO;
use App\Models\Certificado;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\ICertificadoRepository;
use App\Repositories\Contracts\IDetalleParametroRepository;
use App\Repositories\Contracts\IModuloRepository;
use App\Repositories\Contracts\IPersonaRepository;
use App\Repositories\Contracts\IPlantillaRepository;
use App\Repositories\Contracts\IProgramaRepository;
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
use App\Helpers\CertificadoHelper;
use App\Repositories\Contracts\IInstitucionRepository;
use App\Services\Contracts\IStorageService;

class CertificadoService implements ICertificadoService
{
    protected ICertificadoRepository $certificadoRepository;
    protected IPersonaRepository $personaRepository;
    protected IModuloRepository $moduloRepository;
    protected IPlantillaRepository $plantillaRepository;
    protected IDetalleParametroRepository $detalleRepository;
    protected IProgramaRepository $programaRepository;
    protected IInstitucionRepository $institucionRepository;
    protected IStorageService $storageService;

    public function __construct(
        ICertificadoRepository $certificadoRepository,
        IPersonaRepository $personaRepository,
        IModuloRepository $moduloRepository,
        IPlantillaRepository $plantillaRepository,
        IDetalleParametroRepository $detalleRepository,
        IProgramaRepository $programaRepository,
        IInstitucionRepository $institucionRepository,
        IStorageService $storageService
    ) {
        $this->certificadoRepository = $certificadoRepository;
        $this->personaRepository = $personaRepository;
        $this->moduloRepository = $moduloRepository;
        $this->plantillaRepository = $plantillaRepository;
        $this->detalleRepository = $detalleRepository;
        $this->programaRepository = $programaRepository;
        $this->institucionRepository = $institucionRepository;
        $this->storageService = $storageService;
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

        Log::info("Obteniendo información de certificado", ['certificado' => $certificado]);

        // Asegurar que la carpeta storage/fonts exista antes de invocar DomPDF
        CertificadoHelper::ensureFontCacheDirExists();

        // Obtener datos de programa, plantilla y tipo de programa
        $programa = $certificado->programa;
        $plantilla = $certificado->plantilla;
        $tipoPrograma = $programa->tipoPrograma;

        $esCapacitacion = ($tipoPrograma->nombre_url === "capacitacion");
        Log::info("Validando variable esCapacitacion", ['esCapacitacion' => $esCapacitacion]);

        Log::info("Información de plantilla", ["plantilla" => $plantilla]);

        $pdfRelativePath = "{$certificado->path_file}/{$certificado->filename}";

        Log::info("Información de pdf", ["pdfRelativePath" => $pdfRelativePath]);

        // Construir la URL pública de verificación accesible por el escáner del smartphone
        $appUrl = rtrim(config('app.url'), '/');
        $qrUrl = "{$appUrl}/validar-certificado/{$certificado->codigo_verificacion}";

        Log::info("URL de verificación para el código QR", ['qrUrl' => $qrUrl]);

        $this->generateCodeQR($qrUrl, $certificado->codigo_qr_path);

        // Obtener el QR persistido y convertirlo a Base64 para embeder en DomPDF
        // $qrBinary = Storage::disk('local')->get($certificado->codigo_qr_path);
        $qrBinary = $this->storageService->get($certificado->codigo_qr_path);
        $qrBase64 = "data:image/png;base64," . base64_encode($qrBinary);

        // Cargar fondo de pantalla
        $templateBase64 = null;

        // $existPlantilla = $plantilla && $plantilla->path && Storage::disk('public')->exists($plantilla->path);

        // Especificar el disco 'public' ya que las plantillas están almacenadas en /storage/app/public
        $existPlantilla = $plantilla
            && $plantilla->path_imagen_fondo
            && $this->storageService->exists($plantilla->path_imagen_fondo, 'public');

        Log::info("Validando exists plantilla", ['existPlantilla' => $existPlantilla]);

        if ($existPlantilla) {
            Log::info('Validando ruta de plantilla', ['path' => $plantilla->path_imagen_fondo]);

            // $fileData = Storage::disk('public')->get($plantilla->path);

            // Leer el archivo desde el disco 'public'
            $fileData = $this->storageService->get($plantilla->path_imagen_fondo, 'public');

            // Obtener la extensión y construir el mime type manualmente
            $extension = strtolower(pathinfo($plantilla->path_imagen_fondo, PATHINFO_EXTENSION));

            Log::info('Validando extension', ['extension' => $extension]);

            $mime = match ($extension) {
                'png'   => 'image/png',
                'webp'  => 'image/webp',
                'svg'   => 'image/svg+xml',
                default => 'image/jpeg'
            };

            Log::info('Validando mime', ['mime' => $mime]);

            $templateBase64 = "data:{$mime};base64," . base64_encode($fileData);
        }

        // Definir fuentes
        $fonts = [
            'alumno' => CertificadoHelper::getFontBase64('GreatVibes-Regular.ttf'),
            'programa' => $esCapacitacion ? 'Calibri, sans-serif' : CertificadoHelper::getFontBase64('Anton.ttf'),
            'fechas' => $esCapacitacion ? 'Calibri, sans-serif' : CertificadoHelper::getFontBase64('Archivo-Regular.ttf'),
            'director' => CertificadoHelper::getFontBase64('Archivo-Medium.ttf'),
            'is_custom_alumno' => true,
            'is_custom_programa' => !$esCapacitacion,
            'is_custom_fechas' => !$esCapacitacion
        ];

        // Definir las fechas del evento en texto
        $fechaInicio = CertificadoHelper::fechaEnLetras($programa->fecha_inicio ?? null);
        $fechaFinal = CertificadoHelper::fechaEnLetras($programa->fecha_final ?? null);
        $descFechasPrograma = ($fechaInicio && $fechaFinal) ? "Realizado del {$fechaInicio} al {$fechaFinal}" : "";

        // Calcular los estilos dinámicos
        $baseFontSizeAlumno = $esCapacitacion
            ? config('params.styles_pdfs.' . $tipoPrograma->nombre_url . '.fontSize.alumno')
            : config('params.styles_pdfs.baseFontSize.alumno');

        $baseFontSizePrograma = $esCapacitacion
            ? config('params.styles_pdfs.' . $tipoPrograma->nombre_url . '.fontSize.programa')
            : config('params.styles_pdfs.baseFontSize.programa');

        $baseFontSizeFechas = $esCapacitacion
            ? config('params.styles_pdfs.' . $tipoPrograma->nombre_url . '.fontSize.fechas')
            : config('params.styles_pdfs.baseFontSize.fechas');

        $anchoMaximoAlumno = config('params.styles_pdfs.anchoMaximoAlumno');
        $anchoMaximoPrograma = config('params.styles_pdfs.anchoMaximoPrograma');
        $anchoMaximoFechas = config('params.styles_pdfs.anchoMaximoFechas');

        Log::info('Evaluando parámetros de estilos por ancho', [
            'baseFontSizeAlumno'     => $baseFontSizeAlumno,
            'baseFontSizePrograma'   => $baseFontSizePrograma,
            'baseFontSizeFechas'     => $baseFontSizeFechas,
            'anchoMaximoAlumno'  => $anchoMaximoAlumno,
            'anchoMaximoPrograma' => $anchoMaximoPrograma,
            'anchoMaximoFechas' => $anchoMaximoFechas
        ]);

        $estilosAlumno = CertificadoHelper::calcularEstilosTexto($certificado->nombre_impresion, $baseFontSizeAlumno, $anchoMaximoAlumno);
        $estilosPrograma = CertificadoHelper::calcularEstilosTexto($programa->titulo ?? '', $baseFontSizePrograma, $anchoMaximoPrograma);
        $estilosFechas = CertificadoHelper::calcularEstilosTexto($descFechasPrograma, $baseFontSizeFechas, $anchoMaximoFechas);

        Log::info('Evaluando resultados de estilos', [
            'estilosAlumno'   => $estilosAlumno,
            'estilosPrograma' => $estilosPrograma,
            'estilosFechas'   => $estilosFechas,
        ]);

        // Definiendo horas académicas
        $horasAcademicasDefault = config('params.horas_academicas_default');

        Log::info('Evaluando horas académicas', ['horasAcademicasDefault' => $horasAcademicasDefault]);

        // Definiendo nombre director
        Log::info('Evaluando objeto institución', ['institucion' => $plantilla->institucion]);

        $nombreDirector = ($plantilla->institucion && $plantilla->institucion->nombre_director)
            ? $plantilla->institucion->nombre_director
            : "----";

        // Mapear objeto para la vista
        $info = (object)[
            'nombre_alumno'         => $certificado->nombre_impresion,
            'estilos_alumno'        => $estilosAlumno,

            'nombre_tipoprograma'   => $tipoPrograma->nombre ?? 'Programa Académico',
            'nombre_director'       => $nombreDirector,

            'titulo_programa'       => $programa->titulo ?? 'Programa Académico',
            'estilos_programa'      => $estilosPrograma,

            'fechas_programa'       => $descFechasPrograma,
            'estilos_fechas'        => $estilosFechas,

            'horas_academicas'      => $programa->horas_academicas ?? $horasAcademicasDefault,
            'fecha_emision'         => CertificadoHelper::fechaEnLetras($certificado->fecha_crea),
            'codigo_verificacion'   => $certificado->codigo_verificacion,
            'qrCode'                => $qrBase64,
            'fondo'                 => $templateBase64
        ];

        Log::info('Validando información que se creará en el certificado, variable $info', [
            'nombre_alumno'         => $info->nombre_alumno,
            'nombre_tipoprograma'   => $info->nombre_tipoprograma,
            'nombre_director'       => $info->nombre_director,
            'titulo_programa'       => $info->titulo_programa,
            'fechas_programa'       => $info->fechas_programa,
            'horas_academicas'      => $info->horas_academicas,
            'fecha_emision'         => $info->fecha_emision,
        ]);

        // Determinar la plantilla/diseño correspondiente
        $disenio = $plantilla->tipo_disenio;
        $viewNameDefault = CertificadoHelper::resolveTemplateDefault($tipoPrograma->nombre_url);

        Log::info('Validando viewNameDefault', ['viewNameDefault' => $viewNameDefault]);

        // config('params.clases.horas_academicas_default');

        // Definir los parámetros para obtener los estilos en el certificado
        $nombreTipoPrograma = $programa->tipoPrograma->nombre_url;
        $grupoEstilos = "params.styles_pdfs.{$nombreTipoPrograma}.{$disenio}";
        $estilos = config($grupoEstilos);

        Log::info("Validando mapeo de estilos", [
            'nombreTipoPrograma' => $nombreTipoPrograma,
            'grupoEstilos'       => $grupoEstilos,
            'estilos'            => $estilos
        ]);

        $pdf = Pdf::loadView($viewNameDefault, [
            'info' => $info,
            'fonts' => $fonts,
            'estilos' => $estilos
        ])->setPaper('a4', 'landscape')
            ->setOption('isFontSubsettingEnabled', false);

        // Guarda el PDF generado directamente a través de IStorageService
        $this->storageService->put($pdfRelativePath, $pdf->output());

        return $this->storageService->getLocalPath($pdfRelativePath);

        // Storage::disk('local')->put($pdfRelativePath, $pdf->output());
        // return Storage::disk('local')->path($pdfRelativePath);
    }

    public function downloadCertificado(int $id): array
    {
        $certificado = $this->certificadoRepository->findById($id);

        if (!$certificado) {
            throw new Exception("El certificado con ID {$id} no fue encontrado.", 404);
        }

        $relativePath = "{$certificado->path_file}/{$certificado->filename}";

        if (!$this->storageService->exists($relativePath)) {
            $fullPath = $this->generatePDF($id);
        } else {
            $fullPath = $this->storageService->getLocalPath($relativePath);
        }

        // Validación adicional: comprobar que el archivo exista físicamente en el disco
        if (!file_exists($fullPath)) {
            throw new Exception("El archivo del certificado no se encuentra disponible físicamente.", 404);
        }

        $filename = $certificado->filename ?? "Certificado_{$certificado->codigo_verificacion}.pdf";

        return [
            'full_path' => $fullPath,
            'filename'  => $filename,
        ];
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

        $numeroDocumento = $persona->numero_documento ?? $persona->numero_documento ?? 'sin_documento';

        $yearMonthDocDir = 'certificacion' . DIRECTORY_SEPARATOR . date('Y') . DIRECTORY_SEPARATOR . date('m') . DIRECTORY_SEPARATOR . $numeroDocumento;
        Storage::disk('local')->makeDirectory($yearMonthDocDir);

        $urlVerificacion = config('app.url') . "/validar-certificado/" . $codigoVerificacion;
        $qrFilename = "qr_{$codigoVerificacion}.png";
        $qrRelativePath = $yearMonthDocDir . DIRECTORY_SEPARATOR . $qrFilename;

        $this->generateCodeQR($urlVerificacion, $qrRelativePath);
        $qrAbsolutePath = Storage::disk('local')->path($qrRelativePath);

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
        $pdfRelativePath = $yearMonthDocDir . DIRECTORY_SEPARATOR . $pdfFilename;
        $pdfAbsolutePath = Storage::disk('local')->path($pdfRelativePath);

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

        // Obtener la información requerida para construir las rutas jerárquicas
        $persona = $this->personaRepository->findById($data['id_persona']);
        $programa = $this->programaRepository->findById($data['id_programa']);
        // $sucursal = $this->detalleRepository->findByCodigo($data['id_sucursal']);
        $sucursal = $this->institucionRepository->findById($data['id_sucursal']);

        if (!$persona) {
            throw new Exception("La persona especificada no existe.", 404);
        }

        $slugTipoPrograma = Str::slug($programa->tipoPrograma->nombre_url ?? "tipo-programa");
        $slugSucursal = Str::slug($sucursal->nombre ?? "sucursal");
        $dniAlumno    = $persona->numero_documento;

        // Estructura: /{slugTipoPrograma}/{anio}/{nombreSucursal}/{dniAlumno}
        $folderPath = "{$slugTipoPrograma}/{$anio}/{$slugSucursal}/{$dniAlumno}";
        $fileName = "CERT-{$data['codigo_verificacion']}.pdf";
        $qrFileName = "QR_{$data['codigo_verificacion']}.png";

        $data['nombre_impresion'] = $data['nombre_impresion'];
        $data['path_file'] = $folderPath;
        $data['filename'] = $fileName;
        $data['codigo_qr_path'] = "{$folderPath}/{$qrFileName}";

        // Guardar el registro en base de datos
        $certificado = $this->certificadoRepository->create($data);

        if (!$certificado) {
            throw new Exception("No se pudo registrar la información del certificado en la base de datos.");
        }

        return $certificado;
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

        if (!$certificado) {
            throw new \Exception("El certificado con ID {$id} no fue encontrado.", 404);
        }

        return $this->certificadoRepository->delete($id);
    }

    private function generateCodeQR(string $url, string $savePath): string
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

        Storage::disk('local')->put($savePath, $result->getString());

        return $savePath;
    }
}
