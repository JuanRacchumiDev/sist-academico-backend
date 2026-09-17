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
        $sucursal = $certificado->sucursal;

        Log::info("Información de plantilla", ["plantilla" => $plantilla]);

        $pdfRelativePath = "{$certificado->path_file}/{$certificado->filename}";

        Log::info("Información de pdf", ["pdfRelativePath" => $pdfRelativePath]);

        // Construir la URL pública de verificación accesible por el escáner del smartphone
        $frontendUrl = config('app.frontend_url', 'http://localhost:3000');
        $qrUrl = rtrim($frontendUrl, '/') . "/validar-certificado/{$certificado->codigo_verificacion}";

        Log::info("Evaluando frontendUrl", ['frontendUrl' => $frontendUrl]);
        Log::info("URL de verificación para el código QR", ['qrUrl' => $qrUrl]);

        $this->generateCodeQR($qrUrl, $certificado->codigo_qr_path);

        // Obtener el QR persistido y convertirlo a Base64 para embeder en DomPDF
        $qrBinary = $this->storageService->get($certificado->codigo_qr_path);
        $qrBase64 = "data:image/png;base64," . base64_encode($qrBinary);

        // Cargar fondo de pantalla
        $templateBase64 = null;

        // Obteniendo validación si existe plantilla
        $existPlantilla = $plantilla
            && $plantilla->path_imagen_fondo
            && $this->storageService->exists($plantilla->path_imagen_fondo, 'public');

        Log::info("Validando exists plantilla", ['existPlantilla' => $existPlantilla]);

        // Evaluando si existe plantilla
        if ($existPlantilla) {
            $pathImagenFondo = $plantilla->path_imagen_fondo;

            Log::info('Validando ruta de plantilla', ['path' => $pathImagenFondo]);

            // Leer el archivo desde el disco 'public'
            $fileData = $this->storageService->get($pathImagenFondo, 'public');

            // Obtener la extensión y construir el mime type manualmente
            $extension = strtolower(pathinfo($pathImagenFondo, PATHINFO_EXTENSION));

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

        $descFechasPrograma = CertificadoHelper::formatearRangoFechas(
            $programa->fecha_inicio ?? null,
            $programa->fecha_final ?? null
        );

        Log::info('descFechasPrograma', ['descFechasPrograma' => $descFechasPrograma]);

        $nombreTipoPrograma = $tipoPrograma->nombre_url;
        $disenio = $plantilla->tipo_disenio;
        $nombreImpresion = $certificado->nombre_impresion;
        $tituloPrograma = Str::upper($programa->titulo);

        $nombreDirector = ($plantilla->institucion && $plantilla->institucion->nombre_director)
            ? $plantilla->institucion->nombre_director
            : "----";

        $grupoEstilosKey = "params.styles_pdfs.{$nombreTipoPrograma}.{$disenio}";

        Log::info('grupoEstilosKey', ['grupoEstilosKey' => $grupoEstilosKey]);

        $estilos = config($grupoEstilosKey, []);

        if (empty($estilos) && isset(config("params.styles_pdfs.{$nombreTipoPrograma}")['default_uno'])) {
            $estilos = config("params.styles_pdfs.{$nombreTipoPrograma}.default_uno");
        }

        $fontSizeAlumno = $estilos['alumno']['fontSize'] ?? '78';
        $fontSizePrograma = $estilos['programa']['fontSize'] ?? '30';
        $fontSizeFechas = $estilos['fechas']['fontSize'] ?? '17';
        $fontSizeDirector = $estilos['director']['fontSize'] ?? '12';

        $estilosTipoPrograma = "params.styles_pdfs.{$nombreTipoPrograma}";
        $anchoMaximoAlumno   = config("{$estilosTipoPrograma}.anchoMaximoAlumno", 673.60);
        $anchoMaximoPrograma = config("{$estilosTipoPrograma}.anchoMaximoPrograma", 673.60);
        $anchoMaximoFechas   = config("{$estilosTipoPrograma}.anchoMaximoFechas", 673.60);
        $anchoMaximoDirector = config("{$estilosTipoPrograma}.anchoMaximoDirector", 200.00);

        Log::info('Evaluando anchos disponibles', [
            'anchoMaximoAlumno' => $anchoMaximoAlumno,
            'anchoMaximoPrograma' => $anchoMaximoPrograma,
            'anchoMaximoFechas' => $anchoMaximoFechas,
            'anchoMaximoDirector' => $anchoMaximoDirector
        ]);

        // Carga de fuentes (Ruta física de archivos TTF)
        $fontsPath = public_path('fonts') . DIRECTORY_SEPARATOR;

        $buildFontConfig = function (array $seccionConfig, string $defaultFontFile) use ($fontsPath) {
            $isCustom = $seccionConfig['custom_font'] ?? false;
            $fontFile = $seccionConfig['font'] ?? $defaultFontFile;

            return [
                'custom_font' => $isCustom,
                'path' => $isCustom ? $fontsPath . $fontFile : null,
                'font_family' => $isCustom ? 'sans-serif' : ($seccionConfig['font'] ?? 'sans-serif')
            ];
        };

        $fonts = [
            'alumno'   => $buildFontConfig($estilos['alumno'] ?? [], 'GreatVibes-Regular.ttf')['path'],
            'programa' => $buildFontConfig($estilos['programa'] ?? [], 'Anton.ttf')['path'],
            'fechas'   => $buildFontConfig($estilos['fechas'] ?? [], 'Archivo-Regular.ttf')['path'],
            'director' => isset($estilos['director'])
                ? $buildFontConfig($estilos['director'], 'Archivo-Medium.ttf')['path']
                : null,
        ];

        Log::info('fonts', ['fonts' => $fonts]);
        Log::info('estilos', ['estilos' => $estilos]);

        $horasAcademicasDefault = config('params.horas_academicas_default');
        $horasAcademicas = $programa->horas_academicas ?? $horasAcademicasDefault;

        $textoFechasConHoras = ($descFechasPrograma !== '')
            ? "{$descFechasPrograma} con una duración de {$horasAcademicas} horas"
            : "";

        $paramLineHeightAlumno = "params.styles_pdfs.{$nombreTipoPrograma}.{$disenio}.alumno.line_height";
        $paramLineHeightPrograma = "params.styles_pdfs.{$nombreTipoPrograma}.{$disenio}.programa.line_height";
        $paramLineHeightFechas = "params.styles_pdfs.{$nombreTipoPrograma}.{$disenio}.fechas.line_height";
        $paramLineHeightDirector = "params.styles_pdfs.{$nombreTipoPrograma}.{$disenio}.director.line_height";

        $paramFactorConversionAlumno = "params.styles_pdfs.{$nombreTipoPrograma}.{$disenio}.alumno.factor_conversion";
        $paramFactorConversionPrograma = "params.styles_pdfs.{$nombreTipoPrograma}.{$disenio}.programa.factor_conversion";
        $paramFactorConversionFechas = "params.styles_pdfs.{$nombreTipoPrograma}.{$disenio}.fechas.factor_conversion";
        $paramFactorConversionDirector = "params.styles_pdfs.{$nombreTipoPrograma}.{$disenio}.director.factor_conversion";

        $lineHeightAlumno = config($paramLineHeightAlumno, 1.0);
        $lineHeightPrograma = config($paramLineHeightPrograma, 1.0);
        $lineHeightFechas = config($paramLineHeightFechas, 1.0);
        $lineHeightDirector = config($paramLineHeightDirector, 1.0);

        $factorConversionAlumno = config($paramFactorConversionAlumno, 0.35);
        $factorConversionPrograma = config($paramFactorConversionPrograma, 0.35);
        $factorConversionFechas = config($paramFactorConversionFechas, 0.35);
        $factorConversionDirector = config($paramFactorConversionDirector, 0.35);

        Log::info(
            'Evaluando valores lineHeight',
            [
                'lineHeightAlumno' => $lineHeightAlumno,
                'lineHeightPrograma' => $lineHeightPrograma,
                'lineHeightFechas' => $lineHeightFechas,
                'lineHeightDirector' => $lineHeightDirector
            ]
        );

        Log::info(
            'Evaluando valores factorConversion',
            [
                'factorConversionAlumno' => $factorConversionAlumno,
                'factorConversionPrograma' => $factorConversionPrograma,
                'factorConversionFechas' => $factorConversionFechas,
                'factorConversionDirector' => $factorConversionDirector
            ]
        );

        $estilosAlumno   = CertificadoHelper::calcularEstilosTexto($nombreImpresion, $fontSizeAlumno, $anchoMaximoAlumno, $lineHeightAlumno, $factorConversionAlumno);
        $estilosPrograma = CertificadoHelper::calcularEstilosTexto($tituloPrograma ?? '', $fontSizePrograma, $anchoMaximoPrograma, $lineHeightPrograma, $factorConversionPrograma);
        $estilosFechas   = CertificadoHelper::calcularEstilosTexto($textoFechasConHoras, $fontSizeFechas, $anchoMaximoFechas, $lineHeightFechas, $factorConversionFechas);
        $estilosDirector = CertificadoHelper::calcularEstilosTexto($nombreDirector, $fontSizeDirector, $anchoMaximoDirector, $lineHeightDirector, $factorConversionDirector);

        $estilosRender = [
            'alumno' => [
                'color' => $estilos['alumno']['color'] ?? '#000000',
                'fontSize' => $estilosAlumno['font_size'],
                'lineHeight' => $estilosAlumno['line_height']
            ],
            'programa' => [
                'color' => $estilos['programa']['color'] ?? '#000000',
                'fontSize' => $estilosPrograma['font_size'],
                'lineHeight' => $estilosPrograma['line_height']
            ],
            'fechas' => [
                'color' => $estilos['fechas']['color'] ?? '#000000',
                'fontSize' => $estilosFechas['font_size'],
                'lineHeight' => $estilosFechas['line_height']
            ],
            'director' => [
                'color' => $estilos['director']['color'] ?? '#000000',
                'fontSize' => $estilosDirector['font_size'],
                'lineHeight' => $estilosDirector['line_height']
            ]
        ];

        Log::info('estilosRender', ['estilosRender' => $estilosRender]);

        $logoRelative = $sucursal?->logo_path ?? ($plantilla?->institucion?->logo_path ?? null);
        Log::info('Evaluando logoRelative', ['logoRelative' => $logoRelative]);

        $logoBase64 = null;

        if ($logoRelative) {
            // Si logo_path incluye "logo/", se limpia para apuntar exactamente a public/images/logos/
            $cleanPath = ltrim($logoRelative, '/');
            if (str_starts_with($cleanPath, 'logos/')) {
                $cleanPath = substr($cleanPath, 6);
            } elseif (str_starts_with($cleanPath, 'logo/')) {
                $cleanPath = substr($cleanPath, 5);
            }

            $logoPath = public_path('images' . DIRECTORY_SEPARATOR . 'logos' . DIRECTORY_SEPARATOR . $cleanPath);

            Log::info('Evaluando logoPath', ['logoPath' => $logoPath]);

            if (file_exists($logoPath) && is_file($logoPath)) {
                $logoData = file_get_contents($logoPath);
                $logoMime = mime_content_type($logoPath) ?: 'image/jpeg';
                $logoBase64 = "data:{$logoMime};base64," . base64_encode($logoData);
            }
        }

        $info = (object)[
            'nombre_alumno'         => $nombreImpresion,
            // 'estilos_alumno'        => $estilosAlumno,

            'titulo_programa'       => $tituloPrograma ?? 'Programa Académico',
            // 'estilos_programa'      => $estilosPrograma,

            'nombre_tipoprograma'   => $nombreTipoPrograma ?? 'Tipo Programa Académico',

            'fechas_programa'       => $textoFechasConHoras,
            // 'estilos_fechas'        => $estilosFechas,

            'nombre_director'       => $nombreDirector,
            // 'estilos_director'      => $estilosDirector,

            'horas_academicas'      => $horasAcademicas,
            'fecha_emision'         => CertificadoHelper::fechaEnLetras($certificado->fecha_crea),
            'codigo_verificacion'   => $certificado->codigo_verificacion,
            'qrCode'                => $qrBase64,
            'fondo'                 => $templateBase64,
            'logo'                  => $logoBase64,
            'temario'               => $programa->temario ?? ''
        ];

        // Determinar la plantilla/diseño correspondiente
        $viewNameDefault = CertificadoHelper::resolveTemplateDefault($nombreTipoPrograma);

        Log::info('viewNameDefault', ['viewNameDefault' => $viewNameDefault]);

        $pdf = Pdf::loadView($viewNameDefault, [
            'info'    => $info,
            'fonts'   => $fonts,
            'estilos' => $estilosRender
        ])->setPaper('a4', 'landscape')
            ->setOption('isFontSubsettingEnabled', false);

        // Guarda el PDF generado directamente a través de IStorageService
        $this->storageService->put($pdfRelativePath, $pdf->output());

        return $this->storageService->getLocalPath($pdfRelativePath);
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

        $filename = $certificado->filename ?? "certificado_{$certificado->codigo_verificacion}.pdf";

        return [
            'full_path' => $fullPath,
            'filename'  => $filename,
        ];
    }

    /**
     * Obtiene la ruta absoluta y nombre del PDF a partir del código de verificación.
     */
    public function downloadCertificadoByCodigo(string $codigo): array
    {
        $certificado = $this->certificadoRepository->findByCodigo($codigo);

        if (!$certificado) {
            throw new Exception("El certificado con código {$codigo} no fue encontrado.", 404);
        }

        return $this->downloadCertificado($certificado->id);
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

        $idModuloPadding = str_pad($modulo->id, 4, '0', STR_PAD_LEFT);
        $idPersonaPadding = str_pad($persona->id, 4, '0', STR_PAD_LEFT);

        $pdfFilename = "certificado_modulo_{$idModuloPadding}_{$idPersonaPadding}_" . time() . ".pdf";
        $pdfRelativePath = $yearMonthDocDir . DIRECTORY_SEPARATOR . $pdfFilename;
        $pdfAbsolutePath = Storage::disk('local')->path($pdfRelativePath);

        $pdf->Output('F', $pdfAbsolutePath);

        return str_replace('\\', '/', $pdfRelativePath);
    }

    public function getCertificadoById(int $id): ?Certificado
    {
        return $this->certificadoRepository->findById($id);
    }

    /**
     * Obtiene y formatea los datos públicos del certificado para ser presentados en el frontend.
     */
    public function getCertificadoByCodigo(string $codigo): ?Certificado
    {
        $certificado = $this->certificadoRepository->findByCodigo($codigo);

        if (!$certificado) {
            return null;
        }

        return $certificado;
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
            ->size(400)
            ->margin(10)
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->build();

        Storage::disk('local')->put($savePath, $result->getString());

        return $savePath;
    }
}
