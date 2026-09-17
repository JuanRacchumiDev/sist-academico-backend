<?php

namespace App\Services;

use App\DTOs\Programa\ProgramaCreateDTO;
use App\DTOs\Programa\ProgramaUpdateDTO;
use App\Models\Programa;
use App\Repositories\Contracts\IProgramaRepository;
use App\Services\Contracts\IProgramaService;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Override;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProgramaService implements IProgramaService
{
    protected IProgramaRepository $programaRepository;

    public function __construct(IProgramaRepository $programaRepository)
    {
        $this->programaRepository = $programaRepository;
    }

    /**
     * Obtener todos los programas
     * @param array<string, mixed>|null $searchParams
     * @return Collection<int, Programa>
     */
    public function getAllProgramas(?array $searchParams = null): Collection
    {
        return $this->programaRepository->getAll($searchParams);
    }

    /**
     * Obtiene todos los programas con filtros aplicados
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllProgramasWithFilters(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->programaRepository->getAllFiltered($filters, $perPage);
    }



    /**
     * Obtiene un programa por ID
     * @param int $id
     * @return Programa|null
     */
    public function getProgramaById(int $id): ?Programa
    {
        $programa = $this->programaRepository->findById($id);

        if (!$programa) {
            return null;
        }

        if ($programa->relationLoaded('detalleModulos')) {
            $programa->detalleModulos->transform(function ($modulo) use ($id) {
                $hasFile = !empty($modulo->plan) && Storage::disk('local')->exists($modulo->plan);

                $modulo->plan_existe = $hasFile;

                // Generar URL para la descarga si el archivo existe
                $modulo->plan_url = $hasFile
                    ? route('programas.modulos.descargar-plan', [
                        'programaId' => $id,
                        'moduloId' => $modulo->id
                    ])
                    : null;

                return $modulo;
            });
        }

        return $programa;
    }

    public function downloadPlanModulo(int $programaId, int $moduloId): BinaryFileResponse
    {
        $programa = $this->programaRepository->findById($programaId);

        if (!$programa) {
            throw new Exception('El programa solicitado no existe', 404);
        }

        $modulo = $programa->detalleModulos->firstWhere('id', $moduloId);

        if (!$modulo) {
            throw new Exception('El módulo solicitado no pertenece a este programa', 404);
        }

        if (empty($modulo->plan) || !Storage::disk('local')->exists($modulo->plan)) {
            throw new Exception("El archivo del plan de estudios no existe o fue eliminado", 404);
        }

        $fullPath = Storage::disk('local')->path($modulo->plan);

        return response()->download(
            $fullPath,
            "plan_modulo_{$modulo->id}.pdf",
            ["Content-Type", "application/pdf"]
        );
    }

    /**
     * Crear una nuevo programa
     * @param ProgramaCreateDTO $programaCreateDTO
     * @return Programa
     */
    public function createPrograma(ProgramaCreateDTO $programaCreateDTO): Programa
    {
        Log::info('Iniciando creación de programa', [
            'dto_data' => $programaCreateDTO->toArray()
        ]);

        return DB::transaction(function () use ($programaCreateDTO) {
            if ($programaCreateDTO->plan instanceof UploadedFile) {

                // Validar si el archivo llega correctamente
                Log::info('Archivo detectado', [
                    'nombre_original' => $programaCreateDTO->plan->getClientOriginalName(),
                    'mimo_type' => $programaCreateDTO->plan->getMimeType(),
                    'tamaño' => $programaCreateDTO->plan->getSize()
                ]);

                // Obtener el nombre original del archivo
                $originalName = $programaCreateDTO->plan->getClientOriginalName();

                // Almacenar el archivo PDF
                $path = $programaCreateDTO->plan->storeAs(
                    'programas',
                    $originalName,
                    'public'
                );

                $programaCreateDTO->plan = $path;

                Log::info('Archivo almacenado con éxito', ['path' => $path]);
            }

            $programaData = $programaCreateDTO->toArray();

            Log::info("Pasando data al nuevo programa", [
                'data_enviada' => $programaData
            ]);

            $programa = $this->programaRepository->create($programaData);

            // Registro del modelo creado
            Log::info('Programa creado en base de datos', ['id' => $programa->id]);

            return $programa;
        });
    }

    /**
     * Actualizar un programa existente
     * @param int $id
     * @param ProgramaUpdateDTO $programaUpdateDTO
     * @return Programa|null
     */
    public function updatePrograma(int $id, ProgramaUpdateDTO $programaUpdateDTO): ?Programa
    {
        // Registrar el ID y los datos recibidos para actualizar
        Log::info("Intentando actualizar programa ID: {$id}", [
            'data_recibida' => $programaUpdateDTO->toArray()
        ]);

        $data = array_filter($programaUpdateDTO->toArray(), fn($value) => !is_null($value));

        Log::info("Pasando data al programa con id: {$id}", [
            'data_enviada' => $data
        ]);

        $result = $this->programaRepository->update($id, $data);

        if ($result) {
            Log::info("Programa {$id} actualizado correctamente.");
        } else {
            Log::warning("No se pudo actualizar el programa {$id} o no hubo cambios.");
        }

        return $result;
    }
}
