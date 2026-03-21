<?php
namespace App\Services;

use App\DTOs\Matricula\MatriculaCreateDTO;
use App\DTOs\Matricula\MatriculaUpdateDTO;
use App\Models\Matricula;
use App\Repositories\Contracts\IMatriculaRepository;
use App\Services\Contracts\IMatriculaService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MatriculaService implements IMatriculaService {
    protected IMatriculaRepository $matriculaRepository;

    public function __construct(IMatriculaRepository $matriculaRepository) {
        $this->matriculaRepository = $matriculaRepository;
    }

    /**
     * Obtener todas las matrículas
     * @param array<string, mixed>|null $searchParams
     * @return Collection<int, Matricula>
     */
    public function getAllMatriculas(?array $searchParams = null): Collection {
        return $this->matriculaRepository->getAll($searchParams);
    }

    /**
     * Obtiene todos las matrículas con filtros aplicados
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */ 
    public function getAllMatriculasWithFilters(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->matriculaRepository->getAllFiltered($filters, $perPage);
    }

    /**
     * Obtiene una matrícula por ID
     * @param int $id
     * @return Matricula|null
     */
    public function getMatriculaById(int $id): ?Matricula
    {
        return $this->matriculaRepository->findById($id);
    }

    public function generateFichaPDF(int $id)
    {
        $matricula = $this->matriculaRepository->findById($id);
        $institucion = $matricula->institucion;

        $numeroFormateado = str_pad($matricula->id, 6, '0', STR_PAD_LEFT);

        // Definir la ruta: año/institucion/documento/ficha.php
        $anio = Carbon::parse($matricula->fecha_matricula)->year;
        // $nombreIns = strtolower($dataInstitucion->nombre) ?? 'genérico';
        $nombreIns = str($institucion->nombre)->slug();
        $documento = $matricula->persona->numero_documento;

        $folderPath = "matriculas/{$anio}/{$nombreIns}/{$documento}";
        $fileName = "ficha_matricula_{$numeroFormateado}.pdf";
        $fullPath = "{$folderPath}/{$fileName}";

        // Verificar si ya existe
        if (Storage::disk("local")->exists($fullPath)) {
            return Storage::disk("local")->path($fullPath);
        }

        // Si no existe, generarlo
        
        // Generar QR en Base64 para embeberlo en el HTML
        $qrData = route('programas.show', $matricula->id);
        $qrCode = base64_encode(QrCode::format('svg')->size(100)->margin(0)->generate($qrData));

        $logoPath = $institucion->logo_path
            ? storage_path("app/public/".$institucion->logo_path)
            : public_path("images/logo_default.png");

        $logoBase64 = null;

        if (file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);
            $logoBase64 = "data:image/".$type.';base64,'.base64_encode($data);
        }

        $data = [
            'matricula' => $matricula,
            'numero_registro' => $numeroFormateado,
            'institucion' => $institucion,
            'qrCode' => $qrCode,
            'fecha' => now()->format('d/m/Y H:i A'),
            'logo' => $logoBase64,
            'periodo' => $this->getPeriodoAcademico()
        ];

        $pdf = Pdf::loadView('pdf.ficha_matricula', $data)
            ->setPaper('a4', 'portrait');
            // ->setWarnings(false);

        Storage::disk('local')->put($fullPath, $pdf->output());
        return Storage::disk('local')->path($fullPath);
    }

    public function deleteFichaPDF(int $id): bool
    {
        $matricula = $this->matriculaRepository->findById($id);
        if (!$matricula) return false;

        $anio = \Carbon\Carbon::parse($matricula->fecha_matricula)->year;
        $institucion = "peruinnova";
        $documento = $matricula->persona->numero_documento;

        $fullPath = "matriculas/{$anio}/{$institucion}/{$documento}/ficha_matricula_{$id}.pdf";

        if (Storage::disk('local')->exists($fullPath)) {
            return Storage::disk('local')->delete($fullPath);
        }

        return true;
    }

    /**
     * Crear una nueva matrícula
     * @param MatriculaCreateDTO $matriculaCreateDTO
     * @return Matricula
     */
    public function createMatricula(MatriculaCreateDTO $matriculaCreateDTO): Matricula
    {
        return DB::transaction(function() use ($matriculaCreateDTO) {
            $matricutaData = $matriculaCreateDTO->toArray();

            // Extraemos los ids de los programas
            $programasIds = $matricutaData['programas'] ?? [];

            // Quitamos 'programas' del array original
            unset($matricutaData['programas']);

            // Filtrar nulos
            $dataCabecera = array_filter($matricutaData, fn($value) => !is_null($value));

            // Crear la matrícula
            /** @var Matricula $matricula */
            $matricula = $this->matriculaRepository->create($dataCabecera);

            // Crear el detalle de la matrícula
            foreach($programasIds as $idPrograma) {
                $matricula->detalles()->create([
                    'id_programa' => $idPrograma,
                    'user_crea'   => $dataCabecera['user_crea'] ?? null,
                    'estado'      => true
                ]);
            }

            return $matricula->load('detalles.programa');
        });
    }

    public function updateMatricula(int $id, MatriculaUpdateDTO $dto): ?Matricula
    {
        return DB::transaction(function() use ($id, $dto) {
            $data = array_filter($dto->toArray(), fn($v) => !is_null($v));

            $matricula = $this->matriculaRepository->update($id, $data);

            if (isset($data['programas'])) {
                $matricula->detalles()->delete();

                foreach($data['programas'] as $programaId) {
                    $matricula->detalles()->create([
                        'id_programa' => $programaId,
                        'user_crea' => $data['user_actualiza'] ?? null,
                        'estado' => true
                    ]);
                }
            }

            return $matricula->load('detalles');
        });
    }

    private function getPeriodoAcademico(): string
    {
        $mes = now()->month;
        return now()->year.($mes <= 7 ? ' - I': ' - II');
    }
}