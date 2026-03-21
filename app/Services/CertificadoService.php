<?php

namespace App\Services;

use App\DTOs\Certificado\CertificadoCreateDTO;
use App\DTOs\Certificado\CertificadoUpdateDTO;
use App\Models\Certificado;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\ICertificadoRepository;
use App\Repositories\Contracts\IInstitucionRepository;
use App\Repositories\Contracts\IPersonaRepository;
use App\Services\Contracts\ICertificadoService;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificadoService implements ICertificadoService {
    protected ICertificadoRepository $certificadoRepository;
    protected IPersonaRepository $personaRepository;
    protected IInstitucionRepository $institucionRepository;
    
    public function __construct(
        ICertificadoRepository $certificadoRepository,
        IPersonaRepository $personaRepository,
        IInstitucionRepository $institucionRepository
    ) {
        $this->certificadoRepository = $certificadoRepository;
        $this->personaRepository = $personaRepository;
        $this->institucionRepository = $institucionRepository;
    }

    public function getAllCertificadosWithFilters(array $filters, int $perPage): LengthAwarePaginator {
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
            ? storage_path("app/public/".$institucion->logo_path)
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
            $templateBase64 = "data:image/jpeg;base64,".base64_encode(file_get_contents($templatePath));
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

    public function getCertificadoById(int $id): ?Certificado {
        return $this->certificadoRepository->findById($id);
    }

    public function createCertificado(CertificadoCreateDTO $dto): Certificado {
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

    public function updateCertificado(int $id, CertificadoUpdateDTO $dto): ?Certificado {
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
        return now()->year.($mes <= 7 ? ' - I': ' - II');
    }
}