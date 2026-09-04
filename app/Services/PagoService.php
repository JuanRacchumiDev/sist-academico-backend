<?php

namespace App\Services;

use App\DTOs\Pago\PagoCreateDTO;
use App\Models\Pago;
use App\Repositories\Contracts\IPagoRepository;
use App\Repositories\Contracts\IMatriculaRepository;
use App\Services\Contracts\IPagoService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;
use Override;

class PagoService implements IPagoService
{
    protected IPagoRepository $pagoRepository;
    protected IMatriculaRepository $matriculaRepository;

    public function __construct(
        IPagoRepository $pagoRepository,
        IMatriculaRepository $matriculaRepository
    ) {
        $this->pagoRepository = $pagoRepository;
        $this->matriculaRepository = $matriculaRepository;
    }

    public function getAllPagos(?array $searchParams = null): Collection
    {
        return $this->pagoRepository->getAll($searchParams);
    }

    public function getAllPagosWithFilters(array $filters, int $perPage): LengthAwarePaginator
    {
        Log::info('Obteniendo filtros para pago', ['filters' => $filters]);

        return $this->pagoRepository->getAllFiltered($filters, $perPage);
    }

    public function getMatriculaPDF(array $filters): array
    {
        Log::info('Iniciando proceso de PDF de matrícula', ['filters' => $filters]);

        // Verificar si la matrícula ya existe
        if ($this->pagoRepository->existsPDF($filters)) {
            Log::info('Recuperando PDF desde Storage');

            return [
                'pdfContent' => $this->pagoRepository->getPDF($filters),
                'status' => 'success',
                'message' => 'Matrícula recuperada del almacenamiento'
            ];
        }

        // Obtener datos del pago de matrícula
        $pagoMatricula = (object)$this->pagoRepository->getMatriculaData($filters);

        if (!$pagoMatricula) {
            Log::warning('No se encontraron datos para la matrícula', $filters);
            return [
                'pdfContent' => null,
                'status' => 'error',
                'message' => 'Los datos del pago de matrícula no existen en el sistema.'
            ];
        }

        $empresa = [
            'razon_social' => 'COOPERATIVA DE SERVICIOS EDUCACIONALES CAPACITA',
            'ruc' => '20603337337',
            'logo' => public_path('images/LOGO.jpg')
        ];

        $dataPDF = [
            'title' => 'RECIBO DE PAGO DE MATRÍCULA',
            'pago' => $pagoMatricula,
            'empresa' => $empresa
        ];

        $qrContent = json_encode([
            'idPago' => $pagoMatricula->id,
            'fechaPago' => $pagoMatricula->fecha_pago,
            'conceptoPago' => $pagoMatricula->concepto,
            'montoTotal' => $pagoMatricula->monto_total,
            'montoPagado' => $pagoMatricula->monto_pagado,
            'verificacion' => url("/validar/pago/{$pagoMatricula->id}")
        ]);

        // Usamos format('svg') para mejor calidad en el PDF
        $qrCode = base64_encode(QrCode::format('svg')->size(100)->errorCorrection('H')->generate($qrContent));

        $pdf = Pdf::loadView('pdf.pagoMatricula', compact('dataPDF', 'qrCode'));
        $pdfContent = $pdf->output();

        $this->pagoRepository->savePDF($filters, $pdfContent);

        return [
            'pdfContent' => $pdfContent,
            'status' => 'success',
            'message' => 'Matrícula generada y guardada exitosamente'
        ];
    }

    public function getPagoModuloPDF(array $filters): array
    {
        Log::info('Iniciando proceso de PDF de pago de módulo', ['filters' => $filters]);

        // Verificar si el pago de módulo ya existe
        if ($this->pagoRepository->existsPDF($filters)) {
            Log::info('Recuperando PDF desde Storage');

            return [
                'pdfContent' => $this->pagoRepository->getPDF($filters),
                'status' => 'success',
                'message' => 'Módulo de pago recuperado del almacenamiento'
            ];
        }

        // Obtener datos del pago de módulo
        $pagoModulo = (object)$this->pagoRepository->getPagoModuloData($filters);

        if (!$pagoModulo) {
            Log::warning('No se encontraron datos para el pago de módulo', $filters);
            return [
                'pdfContent' => null,
                'status' => 'error',
                'message' => 'Los datos del pago de módulo no existen en el sistema.'
            ];
        }

        $empresa = [
            'razon_social' => 'COOPERATIVA DE SERVICIOS EDUCACIONALES CAPACITA',
            'ruc' => '20603337337',
            'logo' => public_path('images/LOGO.jpg')
        ];

        $qrContent = url("/verificar/recibo-modulo/{$pagoModulo->id}");

        // Usamos format('svg') para mejor calidad en el PDF
        $qrCode = base64_encode(QrCode::format('svg')->size(90)->generate($qrContent));

        $dataPDF = [
            'title' => 'RECIBO DE PAGO DE MÓDULO',
            'pago' => $pagoModulo,
            'empresa' => $empresa,
            'qrCode' => $qrCode
        ];

        $pdf = Pdf::loadView('pdf.pagoModulo', $dataPDF)->setPaper('a4', 'portrait');
        $pdfContent = $pdf->output();

        $this->pagoRepository->savePDF($filters, $pdfContent);

        return [
            'pdfContent' => $pdfContent,
            'status' => 'success',
            'message' => 'Recibo de pago generado y guardado exitosamente'
        ];
    }

    public function getPagoById(int $id): ?Pago
    {
        return $this->pagoRepository->findById($id);
    }

    public function generarConstancia(int $idPago)
    {
        $pago = $this->pagoRepository->findById($idPago);

        if (!$pago) {
            throw new \Exception('El pago especificado no existe');
        }

        $matricula = $pago->matricula;
        $institucion = $pago->institucion ?? $matricula->institucion ?? null;

        // --- Procesamiento del Logo para DomPDF (Base64) ---
        $logoPath = "";
        $logoBase64 = null;

        if ($institucion && $institucion->logo_path) {
            $path = 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'instituciones' . DIRECTORY_SEPARATOR . 'logos' . DIRECTORY_SEPARATOR . $institucion->logo_path;

            Log::info('Evaluando path', ['path' => $path]);

            $customLogoPath = storage_path($path);

            Log::info('Evaluando de customLogoPath', ['customLogoPath' => $customLogoPath]);

            if (file_exists($customLogoPath)) {
                $logoPath = $customLogoPath;
            }
        }

        Log::info('Ruta final del logo a procesar', ['logoPath' => $logoPath]);

        // Convertir la imagen a Base64 para garantizar compatibilidad con DomPdf
        if (file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        // --- Cálculo de Montos para Formas de Pago ---
        $efectivo = (float) ($pago->cantidad_efectivo ?? 0);
        $operacion = (float) ($pago->cantidad_operacion ?? 0);

        // Si no es pago mixto pero los valores vienen en 0, asignamos el total según el tipo
        $totalGeneral = $efectivo + $operacion;

        $dataPdf = [
            'pago'          => $pago,
            'matricula'     => $matricula,
            'institucion'   => $institucion,
            'logoBase64'    => $logoBase64,
            'efectivo'      => $efectivo,
            'operacion'     => $operacion,
            'totalGeneral'  => $totalGeneral,
            'fecha_emision' => now()->format('d/m/Y H:i:s')
        ];

        $pdf = Pdf::loadView('pdfs.constancia_pago', $dataPdf)
            ->setPaper('a4', 'portrait');

        return $pdf->output();
    }

    public function createPago(PagoCreateDTO $pagoCreateDTO): Pago
    {
        $data = array_filter($pagoCreateDTO->toArray(), fn($value) => !is_null($value));

        return $this->pagoRepository->create($data);
    }
}
