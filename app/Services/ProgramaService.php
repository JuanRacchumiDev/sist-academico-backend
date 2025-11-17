<?php
namespace App\Services;

use App\DTOs\Programa\ProgramaCreateDTO;
use App\Models\Programa;
use App\Repositories\Contracts\IProgramaRepository;
use App\Services\Contracts\IProgramaService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProgramaService implements IProgramaService {
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
        return $this->programaRepository->findById($id);
    }

    /**
     * Crear una nuevo programa
     * @param ProgramaCreateDTO $programaCreateDTO
     * @return Programa
     */
    public function createPrograma(ProgramaCreateDTO $programaCreateDTO): Programa
    {
        if ($programaCreateDTO->plan instanceof UploadedFile) {
            // Obtener el nombre original del archivo
            $originalName = $programaCreateDTO->plan->getClientOriginalName();

            // Almacenar el archivo PDF
            $path = $programaCreateDTO->plan->storeAs(
                'programas',
                $originalName,
                'public'
            );

            $programaCreateDTO->plan = $path;
        } else {
            $programaCreateDTO->plan = null;
        }

        $programaData = $programaCreateDTO->toArray();

        return $this->programaRepository->create($programaData);
        
        // $planPath = null;
        // $file = $uploadProgramaDTO->plan_file;
        // $storageDisk = 'public';
        // $directory = 'planes_estudio'; // Directorio público de destino

        // // 1. Procesamiento y Subida del archivo si existe
        // if ($file) {
            
        //     // Obtener nombre original y extensión
        //     $originalName = $file->getClientOriginalName();
        //     $extension = $file->getClientOriginalExtension();

        //     // Preparar un nombre de archivo seguro y slugificado (sin la extensión)
        //     $nameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);
        //     $slugName = Str::slug($nameWithoutExtension);
            
        //     // Reemplazar guiones por guiones bajos (si es la convención deseada)
        //     $safeSlugName = str_replace('-', '_', $slugName);

        //     // Generar el nombre final: {slug}_{timestamp}.{ext} para evitar colisiones
        //     $finalFilename = $safeSlugName . '_' . time() . '.' . $extension;

        //     // Guardar el archivo en el disco 'public' y obtener la ruta
        //     $planPath = Storage::disk($storageDisk)->putFileAs(
        //         $directory,
        //         $file,
        //         $finalFilename
        //     );
        // }

        // // 2. Preparar data para insertar en BD
        // $programaData = [
        //     'sigla' => $uploadProgramaDTO->sigla,
        //     'nombre' => $uploadProgramaDTO->nombre,
        //     'duracion' => $uploadProgramaDTO->duracion,
        //     'modulos' => $uploadProgramaDTO->modulos,
        //     'creditos' => $uploadProgramaDTO->creditos,
        //     'is_vigente' => $uploadProgramaDTO->is_vigente,
        //     'estado' => $uploadProgramaDTO->estado,
        //     'plan' => $planPath // $planPath contendrá la ruta relativa si se subió, o null
        // ];

        // // 3. Añadir campo opcional
        // if (isset($uploadProgramaDTO->codigo_old)) {
        //     $programaData['codigo_old'] = $uploadProgramaDTO->codigo_old;
        // }

        // // 4. Crear el registro
        // return $this->programaRepository->create($programaData);
    }
}