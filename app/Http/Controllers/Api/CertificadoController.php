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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Exception;

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
        try {
            $filters = $request->only([
                'fecha_inicio',
                'fecha_final',
                'search'
            ]);

            $certificados = $this->certificadoService->getAllCertificados($filters);

            if ($certificados->isEmpty()) {
                return response()->json([
                    'result' => true,
                    'data' => [],
                    'message' => 'No se encontraron certificados'
                ], 200);
            }

            return response()->json([
                'result' => true,
                'data' => $certificados,
                'message' => 'Listado de certificados correctos'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching certificados: ' . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al obtener certificados: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getFilteredPaginate(Request $request): JsonResponse
    {
        try {
            $filters = $request->only([
                'fecha_inicio',
                'fecha_final',
                'search'
            ]);

            $perPage = $request->input('per_page', 10);

            $certificados = $this->certificadoService->getAllCertificadosWithFilters($filters, $perPage);

            if ($certificados->isEmpty()) {
                return response()->json([
                    'result' => false,
                    'data' => [],
                    'message' => 'No se encontraron certificados'
                ], 200);
            }

            return response()->json([
                'result' => true,
                'data' => $certificados,
                'message' => 'Resultados encontrados correctamente',
                'pagination' => [
                    'total' => $certificados->total(),
                    'per_page' => $certificados->perPage(),
                    'current_page' => $certificados->currentPage(),
                    'last_page' => $certificados->lastPage(),
                    'from' => $certificados->firstItem(),
                    'to' => $certificados->lastItem()
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error filtering certificados: " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al obtener certificados.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function download(int $id): BinaryFileResponse|JsonResponse
    {
        try {
            $fileData = $this->certificadoService->downloadCertificado($id);

            return response()->download($fileData['full_path'], $fileData['filename'], [
                'Content-Type' => 'application/pdf',
            ]);
        } catch (Exception $e) {
            $statusCode = $e->getCode() === 404 ? 404 : 500;

            Log::error("Error al descargar el certificado {$id}: " . $e->getMessage());

            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], $statusCode);
        }
    }

    public function downloadPDF(int $id): BinaryFileResponse|JsonResponse
    {
        try {
            $fileData = $this->certificadoService->downloadCertificado($id);

            return response()->file($fileData['full_path'], [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $fileData['filename'] . '"'
            ]);
        } catch (Exception $e) {
            $statusCode = $e->getCode() === 404 ? 404 : 500;

            Log::error("Error al previsualizar el certificado {$id}: " . $e->getMessage());

            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], $statusCode);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->all();

            $usuarioAutenticado = Auth::user();
            $username = $usuarioAutenticado ? ($usuarioAutenticado->name) : 'systemapi';
            $data['user_crea'] = $username;

            $dto = CertificadoCreateDTO::from($data);

            $certificado = $this->certificadoService->createCertificado($dto);

            $this->certificadoService->generatePDF($certificado->id);

            return response()->json([
                'result'  => true,
                'data'    => $certificado,
                'message' => 'Certificado y PDF generados exitosamente',
                'code'    => 'CORRECT_RECORDED'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'result'  => false,
                'message' => 'Validation error',
                'errors'  => $e->errors(),
                'code'    => 'INVALID_RECORD'
            ], 422);
        } catch (Exception $e) {
            Log::error("Error al crear el certificado: " . $e->getMessage());

            return response()->json([
                'result'  => false,
                'message' => 'Error al crear el certificado: ' . $e->getMessage(),
                'code'    => 'INVALID_RECORD'
            ], 500);
        }
    }

    public function storeModular(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'id_persona'         => 'required|integer|exists:persona,id',
            'id_modulo'          => 'required|integer|exists:modulo,id',
            'id_plantilla'       => 'required|integer|exists:plantilla,id',
            'id_institucion'     => 'required|integer|exists:institucion,id',
            'id_tipocertificado' => 'required|integer',
            'id_programa'        => 'required|integer|exists:programa,id',
        ]);

        try {
            return DB::transaction(function () use ($validatedData) {
                // Generar un código único de verificación
                $codigoVerificacion = strtoupper(Str::random(10));
                $validatedData['codigo_verificacion'] = $codigoVerificacion;
                $validatedData['estado'] = true;

                // Crear el DTO y guardar el registro inicial en DB
                $dto = CertificadoCreateDTO::from($validatedData);
                $certificado = $this->certificadoService->createCertificado($dto);

                // Generando el archivo PDF usando FPDI y QR
                $pdfRelativePath = $this->certificadoService->generateCertificadoModular([
                    'id_persona'          => $certificado->id_persona,
                    'id_modulo'           => $validatedData['id_modulo'],
                    'id_plantilla'        => $certificado->id_plantilla,
                    'codigo_verificacion' => $certificado->codigo_verificacion
                ]);

                // Actualizar el registro con los paths reales del PDF generado
                $filename = basename($pdfRelativePath);
                $pathFile = dirname($pdfRelativePath);

                $updateDTO = CertificadoUpdateDTO::from([
                    'path_file' => $pathFile,
                    'filename'  => $filename,
                ]);

                $certificadoActualizado = $this->certificadoService->updateCertificado($certificado->id, $updateDTO);

                return response()->json([
                    'status'  => 'success',
                    'message' => 'Certificado modular generado y guardado exitosamente.',
                    'data'    => $certificadoActualizado
                ], 201);
            });
        } catch (Exception $e) {
            Log::error('Error al generar certificado modular: ' . $e->getMessage());

            return response()->json([
                'status'  => 'error',
                'message' => 'Error al generar el certificado: ' . $e->getMessage()
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
    // public function update(int $id, Request $request): JsonResponse
    // {
    //     try {
    //         $data = $request->all();

    //         $usuarioAutenticado = Auth::user();
    //         $username = $usuarioAutenticado ? ($usuarioAutenticado->name) : 'systemapi';
    //         $data['user_actualiza'] = $username;

    //         $dto = CertificadoUpdateDTO::from($data);

    //         $certificado = $this->certificadoService->updateCertificado($id, $dto);

    //         if (!$certificado) {
    //             return response()->json([
    //                 'result' => false,
    //                 'data' => [],
    //                 'message' => 'Certificado no encontrado o no se pudo actualizar'
    //             ], 404);
    //         }

    //         $this->certificadoService->generatePDF($id);

    //         return response()->json([
    //             'result' => true,
    //             'data' => $certificado,
    //             'message' => 'Certificado actualizo correctamente'
    //         ], 200);
    //     } catch (ValidationException $e) {
    //         return response()->json([
    //             'result' => false,
    //             'message' => 'Validation error',
    //             'errors' => $e->errors()
    //         ], 422);
    //     } catch (\Exception $e) {
    //         $message = 'Error al actualizar el certificado:' . $e->getMessage();

    //         Log::error("Error updating certificado: " . $e->getMessage());

    //         return response()->json([
    //             'result' => false,
    //             'message' => $message
    //         ], 500);
    //     }
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $deleted = $this->certificadoService->deleteCertificado($id);

            if (!$deleted) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'No se encontró el certificado o no se pudo eliminar.'
                ], 404);
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'Certificado eliminado correctamente.'
            ], 200);
        } catch (Exception $e) {
            Log::error("Error al eliminar el certificado {$id}: " . $e->getMessage());

            return response()->json([
                'status'  => 'error',
                'message' => 'Error al intentar eliminar el certificado.'
            ], 500);
        }
    }
}
