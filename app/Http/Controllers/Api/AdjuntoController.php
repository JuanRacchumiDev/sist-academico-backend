<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Adjunto\AdjuntoCreateDTO;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\DTOs\Adjunto\AdjuntoCreateDTO;
use App\Services\AdjuntoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Exception;

class AdjuntoController extends Controller
{
    protected AdjuntoService $adjuntoService;

    public function __construct(AdjuntoService $adjuntoService)
    {
        $this->adjuntoService = $adjuntoService;
    }

    public function index(): JsonResponse
    {
        try {
            $adjuntos = $this->adjuntoService->getAllAdjuntos();

            if ($adjuntos->isEmpty()) {
                return response()->json([
                    'result' => true,
                    'data' => [],
                    'message' => 'No se encontraron adjuntos'
                ], 200);
            }

            return response()->json([
                'result' => true,
                'data' => $adjuntos,
                'message' => 'Listado de adjuntos correctos'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching adjuntos: ' . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al obtener adjuntos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function getFilteredPaginate(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['id_programa', 'id_modulo', 'search', 'fecha_inicio', 'fecha_final']);

            $perPage = (int)$request->input('per_page', 10);

            $adjuntos = $this->adjuntoService->getAllAdjuntosWithFilters($filters, $perPage);

            if ($adjuntos->isEmpty()) {
                return response()->json([
                    'result' => false,
                    'data' => [],
                    'message' => 'No se encontraron resultados'
                ], 200);
            }

            return response()->json([
                'result' => true,
                'data' => $adjuntos->items(),
                'message' => $adjuntos->isEmpty() ? 'No se encontraron resultados' : 'Listado de adjuntos correctos',
                'pagination' => [
                    'total' => $adjuntos->total(),
                    'per_page' => $adjuntos->perPage(),
                    'current_page' => $adjuntos->currentPage(),
                    'last_page' => $adjuntos->lastPage()
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching adjuntos: " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al obtener adjuntos: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function download(int $id): BinaryFileResponse|JsonResponse
    {
        try {
            $downloadData = $this->adjuntoService->getDownloadData($id);

            return response()->download(
                $downloadData['fullpath'],
                $downloadData['filename'],
                ['Content-Type' => $downloadData['mimetype']]
            );
        } catch (Exception $ex) {
            Log::error("Error al descargar adjunto (ID: {$id}): " . $ex->getMessage());

            $statusCode = ($ex->getCode() >= 400 && $ex->getCode() < 600) ? $ex->getCode() : 500;

            return response()->json([
                'result'  => false,
                'message' => $ex->getMessage() ?: 'Error al procesar la descarga'
            ], $statusCode);
        }
    }

    public function verificarExistencia(Request $request): JsonResponse
    {
        try {
            $filters = $request->validate([
                'id_programa' => 'required|integer|exists:programa,id',
                'id_modulo'   => 'sometimes|nullable|integer|exists:modulo,id',
                'titulo'      => 'required|string|max:100'
            ]);

            $adjuntoExistente = $this->adjuntoService->obtenerAdjuntoByParams(
                (int)$filters['id_programa'],
                !empty($filters['id_modulo']) ? (int)$filters['id_modulo'] : null,
                $filters['titulo']
            );

            if ($adjuntoExistente) {
                return response()->json([
                    'result'  => true,
                    'exists'  => true,
                    'data'    => $adjuntoExistente,
                    'message' => 'El adjunto ya ha sido ingresado previamente',
                    'code'    => 'PREVIOUSLY_REGISTERED'
                ], 200);
            }

            return response()->json([
                'result'  => true,
                'exists'  => false,
                'message' => 'El adjunto no existe',
                'code'    => 'NOT_REGISTERED'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'result'  => false,
                'message' => 'Error de validación',
                'errors'  => $e->errors(),
                'code'    => 'INVALID_FILTERS'
            ], 422);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdjuntoCreateDTO $dto, Request $request): JsonResponse
    {
        try {
            $idPrograma = $dto->id_programa;
            $idModulo = $dto->id_modulo ?? null;
            $titulo = $dto->titulo;

            $adjuntoExistente = $this->adjuntoService->obtenerAdjuntoByParams($idPrograma, $idModulo, $titulo);

            if ($adjuntoExistente) {
                return response()->json([
                    'result' => true,
                    'data' => $adjuntoExistente,
                    'message' => 'El adjunto ya ha sido ingresado previamente',
                    'code' => 'PREVIOUSLY_REGISTERED'
                ], 200);
            }

            $usuarioAutenticado = Auth::user();
            $username = $usuarioAutenticado ? ($usuarioAutenticado->name) : 'systemapi';

            $data = $dto->toArray();
            $data['user_crea'] = $username;
            $data['id_modulo'] = $idModulo;

            $adjunto = $this->adjuntoService->createAdjunto($data, $request->file('file'));

            return response()->json([
                'result' => true,
                'data' => $adjunto,
                'message' => 'Adjunto registrado correctamente',
                'code' => 'CORRECT_RECORDED'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'result' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
                'code' => 'INVALID_RECORD'
            ], 422);
        } catch (\Exception $e) {
            Log::error("Error al crear el registro de adjunto: " . $e->getMessage());

            $statusCode = ($e->getCode() >= 400 && $e->getCode() < 600) ? $e->getCode() : 500;

            return response()->json([
                'result' => false,
                'message' => 'Error al crear el registro: ' . $e->getMessage(),
                'code' => 'INVALID_RECORD'
            ], $statusCode);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $adjunto = $this->adjuntoService->getAdjuntoById($id);

            if (!$adjunto) {
                return response()->json([
                    'result' => false,
                    'message' => 'Adjunto no encontrado',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'result' => true,
                'data' => $adjunto,
                'message' => 'Adjunto encontrado correctamente'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching adjunto (id: {$id}): " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al obtener el adjunto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Obtenemos los datos excepto el archivo para su tratamiento posterior
            $data = $request->except('file');
            $file = $request->file('file');

            $adjunto = $this->adjuntoService->updateAdjunto((int)$id, $data, $file);

            if (!$adjunto) {
                return response()->json([
                    'result' => false,
                    'message' => 'No se pudo encontrar o actualizar el adjunto especificado',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'result' => true,
                'data' => $adjunto,
                'message' => 'Adjunto actualizado correctamente'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error actualizando adjunto (id: {$id}): " . $e->getMessage());

            $statusCode = ($e->getCode() >= 400 && $e->getCode() < 600) ? $e->getCode() : 500;

            return response()->json([
                'result' => false,
                'message' => 'Error al actualizar el adjunto: ' . $e->getMessage()
            ], $statusCode);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->adjuntoService->deleteAdjunto($id);

            return response()->json([
                'result'  => true,
                'message' => 'Adjunto y archivo físico eliminados correctamente'
            ], 200);
        } catch (Exception $e) {
            Log::error("Error eliminando adjunto (ID: {$id}): " . $e->getMessage());

            $statusCode = ($e->getCode() >= 400 && $e->getCode() < 600) ? $e->getCode() : 500;

            return response()->json([
                'result'  => false,
                'message' => 'Error al eliminar el adjunto: ' . $e->getMessage()
            ], $statusCode);
        }
    }
}
