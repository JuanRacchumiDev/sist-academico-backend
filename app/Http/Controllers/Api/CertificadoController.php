<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DTOs\Certificado\CertificadoCreateDTO;
use App\DTOs\Certificado\CertificadoUpdateDTO;
use App\Services\Contracts\ICertificadoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CertificadoController extends Controller
{
    protected ICertificadoService $certificadoService;

    public function __construct(ICertificadoService $certificadoService)
    {
        $this->certificadoService = $certificadoService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $filtros = $request->only(['id_persona', 'id_tipocertificado', 'estado', 'search', 'per_page']);
        $certificados = $this->certificadoService->getAllCertificadosWithFilters($filtros, 10);
        return response()->json($certificados);
    }

    public function download(int $id) {
        try {
            $certificado = $this->certificadoService->getCertificadoById($id);

            if (!$certificado) {
                return response()->json([
                    'result' => false,
                    'message' => 'No existe el certificado'
                ], 404);
            }

            // Ruta completa del archivo
            $fullPath = storage_path("app/local/{$certificado->path_file}/{$certificado->filename}");

            // Si el archivo no existe, lo generamos
            if (!file_exists($fullPath)) {
                $this->certificadoService->generatePDF($id);
            }

            return response()->download($fullPath, $certificado->fullname, [
                'Content-Type' => 'application/pdf'
            ]);
        } catch (\Exception $e) {
            Log::error("Error al descargar PDF: " . $e->getMessage());
            return response()->json(['result' => false, 'message' => 'Error al procesar el archivo'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->all();

            $dto = CertificadoCreateDTO::from($data);

            $certificado = $this->certificadoService->createCertificado($dto);

            $this->certificadoService->generatePDF($certificado->id);

            return response()->json([
                'result' => true,
                'data' => $certificado,
                'message' => 'Certificado y PDF generados exitosamente',
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
             Log::error("Error al crear el certificado: " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al crear el certificado: ' . $e->getMessage(),
                'code' => 'INVALID_RECORD'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $certificado = $this->certificadoService->getCertificadoById($id);

            if (!$certificado) {
                return response()->json([
                    'result' => false,
                    'message' => 'Certificado no encontrado',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'result' => true,
                'message' => 'Certificado obtenida exitosamente',
                'data' => $certificado
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching certificado: " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al obtener el certificado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $data = $request->all();

            $dto = CertificadoUpdateDTO::from($data);

            $certificado = $this->certificadoService->updateCertificado($id, $dto);

            if (!$certificado) {
                return response()->json([
                    'result' => false,
                    'data' => [],
                    'message' => 'Certificado no encontrado o no se pudo actualizar'
                ], 404);
            }

            $this->certificadoService->generatePDF($id);

            return response()->json([
                'result' => true,
                'data' => $certificado,
                'message' => 'Certificado actualizo correctamente'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'result' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            $message = 'Error al actualizar el certificado:' . $e->getMessage();

            Log::error("Error updating certificado: " . $e->getMessage());
            
            return response()->json([
                'result' => false,
                'message' => $message
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $deleted = $this->certificadoService->deleteCertificado($id);

            return response()->json([
               'result' => $deleted,
               'message' => $deleted ? 'Eliminado' : 'No encontrado' 
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'result' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
