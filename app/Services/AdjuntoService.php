<?php

namespace App\Services;

use App\DTOs\Adjunto\AdjuntoCreateDTO;
use App\Models\Adjunto;
use App\Repositories\Contracts\IAdjuntoRepository;
use App\Services\Contracts\IAdjuntoService;
use App\Services\Contracts\IStorageService;
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
    protected IStorageService $storageService;

    public function __construct(
        IAdjuntoRepository $adjuntoRepository,
        IStorageService $storageService
    ) {
        $this->adjuntoRepository = $adjuntoRepository;
        $this->storageService = $storageService;
    }

    public function getAllAdjuntos(?array $searchParams = null): Collection
    {
        return $this->adjuntoRepository->getAll($searchParams);
    }

    public function getAllAdjuntosWithFilters(array $filters, int $perPage): LengthAwarePaginator
    {
        Log::info('Evaluando filters', ['filters' => $filters]);

        return $this->adjuntoRepository->getAllFiltered($filters, $perPage);
    }

    public function getAdjuntoById(int $id): ?Adjunto
    {
        return $this->adjuntoRepository->findById($id);
    }

    public function obtenerAdjuntoByParams(int $idPrograma, ?int $idModulo, string $titulo): ?Adjunto
    {
        return $this->adjuntoRepository->findDuplicate($idPrograma, $idModulo, $titulo);
    }

    /**
     * Obtiene los datos necesarios para descargar el archivo usando StorageService
     */
    public function getDownloadData(int $id): array
    {
        $adjunto = $this->adjuntoRepository->findById($id);

        if (!$adjunto) {
            throw new Exception("El registro del archivo adjunto con ID {$id} no fue encontrado.", 404);
        }

        if (!$this->storageService->exists($adjunto->filepath)) {
            throw new Exception("El archivo físico no se encuentra disponible en el almacenamiento.", 404);
        }

        $localPath = $this->storageService->getLocalPath($adjunto->filepath);

        return [
            'fullpath' => $localPath,
            'filename' => $adjunto->originalname ?? $adjunto->filename,
            'mimetype' => $adjunto->mimetype
        ];
    }

    public function createAdjunto(array $data, UploadedFile $file): Adjunto
    {
        return DB::transaction(function () use ($data, $file) {

            Log::info('Obteniendo data para nuevo adjunto', ['data' => $data]);

            $directory = 'adjuntos';

            if (!empty($data['id_programa'])) {
                $directory .= '/programa/' . $data['id_programa'];
                if (!empty($data['id_modulo'])) {
                    $directory .= '/modulo/' . $data['id_modulo'];
                }
            }

            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $filePath = "{$directory}/{$filename}";

            // Subir archivo mediante StorageService
            $stored = $this->storageService->put($filePath, file_get_contents($file->getRealPath()));

            if (!$stored) {
                throw new Exception("Error al guardar el archivo físico en el almacenamiento.", 500);
            }

            try {
                return DB::transaction(function () use ($data, $file, $filename, $filePath) {
                    $fullData = array_merge($data, [
                        'titulo_url'     => Str::slug($data['titulo']),
                        'filename'       => $filename,
                        'originalname'   => $file->getClientOriginalName(),
                        'filepath'       => $filePath,
                        'mimetype'       => $file->getClientMimeType(),
                        'size'           => $file->getSize(),
                        'is_descargable' => filter_var($data['is_descargable'] ?? true, FILTER_VALIDATE_BOOLEAN),
                        'is_visible'     => filter_var($data['is_visible'] ?? true, FILTER_VALIDATE_BOOLEAN),
                        'id_modulo'      => !empty($data['id_modulo']) ? (int)$data['id_modulo'] : null,
                        'id_sucursal'    => !empty($data['id_sucursal']) ? (int)$data['id_sucursal'] : null,
                        'estado'         => true
                    ]);

                    $adjuntoCreateDTO = AdjuntoCreateDTO::from($fullData);
                    return $this->adjuntoRepository->create($adjuntoCreateDTO->toArray());
                });
            } catch (Exception $e) {
                // Rollback manual del archivo físico si la transacción de BD falla
                $this->storageService->delete($filePath);
                throw $e;
            }
        });
    }

    /**
     * Actualizar adjunto y reemplazar archivo en StorageService si se provee uno nuevo
     */
    public function updateAdjunto(int $id, array $data, ?UploadedFile $file = null): ?Adjunto
    {
        $adjunto = $this->adjuntoRepository->findById($id);

        if (!$adjunto) {
            throw new Exception("El adjunto con ID {$id} no fue encontrado.", 404);
        }

        $oldFilePath = $adjunto->filepath;
        $newFilePath = null;

        $updateData = [
            'titulo'      => $data['titulo'] ?? $adjunto->titulo,
            'titulo_url'  => isset($data['titulo']) ? Str::slug($data['titulo']) : $adjunto->titulo_url,
            'id_programa' => !empty($data['id_programa']) ? (int)$data['id_programa'] : $adjunto->id_programa,
            'id_modulo'   => isset($data['id_modulo']) ? (!empty($data['id_modulo']) ? (int)$data['id_modulo'] : null) : $adjunto->id_modulo,
            'id_sucursal' => isset($data['id_sucursal']) ? (!empty($data['id_sucursal']) ? (int)$data['id_sucursal'] : null) : $adjunto->id_sucursal,
        ];

        if ($file) {
            $directory = 'adjuntos';
            $progId = $updateData['id_programa'];
            $modId  = $updateData['id_modulo'];

            if (!empty($progId)) {
                $directory .= '/programa/' . $progId;
                if (!empty($modId)) {
                    $directory .= '/modulo/' . $modId;
                }
            }

            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $newFilePath = "{$directory}/{$filename}";

            $stored = $this->storageService->put($newFilePath, file_get_contents($file->getRealPath()));

            if (!$stored) {
                throw new Exception("Error al subir el nuevo archivo al almacenamiento.", 500);
            }

            $updateData['filename']     = $filename;
            $updateData['originalname'] = $file->getClientOriginalName();
            $updateData['filepath']     = $newFilePath;
            $updateData['mimetype']     = $file->getClientMimeType();
            $updateData['size']         = $file->getSize();
        }

        try {
            $adjuntoActualizado = DB::transaction(function () use ($id, $updateData) {
                return $this->adjuntoRepository->update($id, $updateData);
            });

            // Eliminar archivo antiguo si el nuevo se subió con éxito
            if ($file && $newFilePath && $oldFilePath && $oldFilePath !== $newFilePath) {
                $this->storageService->delete($oldFilePath);
            }

            return $adjuntoActualizado;
        } catch (Exception $e) {
            if ($newFilePath) {
                $this->storageService->delete($newFilePath);
            }
            throw $e;
        }
    }

    /**
     * Eliminar adjunto tanto de la BD como del Storage
     */
    public function deleteAdjunto(int $id): bool
    {
        $adjunto = $this->adjuntoRepository->findById($id);

        if (!$adjunto) {
            throw new Exception("El adjunto con ID {$id} no fue encontrado.", 404);
        }

        return DB::transaction(function () use ($adjunto) {
            $filePath = $adjunto->filepath;

            // 1. Eliminar registro BD
            $deleted = $this->adjuntoRepository->delete($adjunto->id);

            // 2. Eliminar archivo físico
            if ($deleted && !empty($filePath) && $this->storageService->exists($filePath)) {
                $this->storageService->delete($filePath);
            }

            return $deleted;
        });
    }
}
