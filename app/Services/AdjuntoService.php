<?php

namespace App\Services;

use App\DTOs\Adjunto\AdjuntoCreateDTO;
use App\Models\Adjunto;
use App\Repositories\Contracts\IAdjuntoRepository;
use App\Services\Contracts\IAdjuntoService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Override;

class AdjuntoService implements IAdjuntoService
{
    protected IAdjuntoRepository $adjuntoRepository;

    public function __construct(IAdjuntoRepository $adjuntoRepository)
    {
        $this->adjuntoRepository = $adjuntoRepository;
    }

    public function getAllAdjuntos(?array $searchParams = null): Collection
    {
        return $this->adjuntoRepository->getAll($searchParams);
    }

    public function getAllAdjuntosWithFilters(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->adjuntoRepository->getAllFiltered($filters, $perPage);
    }

    public function getAdjuntoById(int $id): ?Adjunto
    {
        return $this->adjuntoRepository->findById($id);
    }

    public function getDownloadData(int $id): array
    {
        $adjunto = $this->adjuntoRepository->findById($id);

        if (!$adjunto) {
            throw new Exception("El registro del archivo adjunto no fue encontrado.", 404);
        }

        // Verificar existencia en el disco public
        if (!Storage::disk('public')->exists($adjunto->filepath)) {
            throw new Exception("El archivo físico no existe en el servidor.", 404);
        }

        // Obtener ruta absoluta física del archivo
        $absolutePath = Storage::disk('public')->path($adjunto->filepath);
        $filename = $adjunto->originalname ?? basename($adjunto->filepath);

        return [
            'fullpath' => $absolutePath,
            'filename' => $filename
        ];
    }

    public function obtenerAdjuntoByParams(int $idPrograma, ?int $idModulo, string $titulo): ?Adjunto
    {
        return $this->adjuntoRepository->findDuplicate($idPrograma, $idModulo, $titulo);
    }

    // public function download(string $id): StreamedResponse | JsonResponse
    // {
    //     try {
    //         $adjunto = $this->getAdjuntoById((int)$id);

    //         if (!$adjunto || Storage::disk('public')->exists($adjunto->filepath)) {
    //             return response()->json([
    //                 'result' => false,
    //                 'message' => 'El archivo no existe en el servidor'
    //             ], 404);
    //         }

    //         // Retorna la descarga binaria con el nombre original de la BD
    //         return Storage::disk('public')->download(
    //             $adjunto->filepath,
    //             $adjunto->originalname ?? basename($adjunto->filepath)
    //         );
    //     } catch (\Exception $ex) {
    //         Log::error("Error al descargar adjunto (id: {$id}): " . $e->getMessage());
    //         return response()->json([
    //             'result' => false,
    //             'message' => 'Error al procesar la descarga'
    //         ], 500);
    //     }
    // }

    public function createAdjunto(array $data, UploadedFile $file): Adjunto
    {
        return DB::transaction(function () use ($data, $file) {

            Log::info('Obteniendo data para nuevo adjunto', ['data' => $data]);

            $directory = 'adjuntos';

            if (isset($data['id_programa']) && !empty($data['id_programa'])) {
                $directory .= '/programa/' . $data['id_programa'];

                if (isset($data['id_modulo']) && !empty($data['id_modulo'])) {
                    $directory .= '/modulo/' . $data['id_modulo'];
                }
            }

            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs($directory, $filename, 'public');

            Log::info('Iniciando creación de adjunto', [
                'filename' => $filename,
                'path' => $path
            ]);

            $fullData = array_merge($data, [
                'titulo_url' => Str::slug($data['titulo']),
                'filename' => $filename,
                'originalname' => $file->getClientOriginalName(),
                'filepath' => $path,
                'mimetype' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'is_descargable' => filter_var($data['is_descargable'] ?? true, FILTER_VALIDATE_BOOLEAN),
                'is_visible' => filter_var($data['is_visible'] ?? true, FILTER_VALIDATE_BOOLEAN),
                'id_modulo' => (isset($data['id_modulo']) && !empty($data['id_modulo'])) ? (int)$data['id_modulo'] : null,
                'id_sucursal' => (isset($data['id_sucursal']) && !empty($data['id_sucursal'])) ? (int)$data['id_sucursal'] : null,
                'estado' => true
            ]);

            Log::info('Definiendo data para crear adjunto', ['fullData' => $fullData]);

            $adjuntoCreateDTO = AdjuntoCreateDTO::from($fullData);

            Log::info('Definiendo adjuntoCreateDTO', ['adjuntoCreateDTO' => $adjuntoCreateDTO]);

            $dataToCreate = $adjuntoCreateDTO->toArray();

            Log::info('Definiendo dataToCreate', ['dataToCreate' => $dataToCreate]);

            $adjunto = $this->adjuntoRepository->create($dataToCreate);

            return $adjunto;
        });
    }

    public function updateAdjunto(int $id, array $data, ?UploadedFile $file = null): ?Adjunto
    {
        return DB::transaction(function () use ($id, $data, $file) {
            $adjunto = $this->adjuntoRepository->findById($id);

            if (!$adjunto) {
                return null;
            }

            Log::info('Datos recibidos para actualizar adjunto', ['id' => $id, 'data' => $data]);

            // Mapeo inicial con los datos existentes
            $updateData = [
                'titulo' => $data['titulo'] ?? $adjunto->titulo,
                'titulo_url' => isset($data['titulo']) ? Str::slug($data['titulo']) : $adjunto->titulo_url,
                'id_programa' => (isset($data['id_programa']) && !empty($data['id_programa'])) ? (int)$data['id_programa'] : null,
                'id_modulo' => (isset($data['id_modulo']) && !empty($data['id_modulo'])) ? (int)$data['id_modulo'] : null,
                'id_sucursal' => (isset($data['id_sucursal']) && !empty($data['id_sucursal'])) ? (int)$data['id_sucursal'] : null,
            ];

            // Solo si se subió un nuevo archivo binario re-calculamos rutas y propiedades físicas
            if ($file) {
                $directory = 'adjuntos';
                if (!empty($updateData['id_programa'])) {
                    $directory .= '/programa/' . $updateData['id_programa'];
                    if (!empty($updateData['id_modulo'])) {
                        $directory .= '/modulo/' . $updateData['id_modulo'];
                    }
                }

                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs($directory, $filename, 'public');

                // Opcional: Eliminar archivo anterior físicamente usando Storage::disk('public')->delete($adjunto->filepath);

                $updateData['filename'] = $filename;
                $updateData['originalname'] = $file->getClientOriginalName();
                $updateData['filepath'] = $path;
                $updateData['mimetype'] = $file->getClientMimeType();
                $updateData['size'] = $file->getSize();
            }

            $adjuntoActualizado = $this->adjuntoRepository->update($id, $updateData);

            Log::info('Adjunto actualizado en repositorio exitosamente', ['adjunto' => $adjuntoActualizado]);

            return $adjuntoActualizado;
        });
    }
}
