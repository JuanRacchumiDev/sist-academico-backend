<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DTOs\Certificado\CertificadoCreateDTO;
use App\DTOs\Certificado\CertificadoUpdateDTO;
use App\Helpers\FechaHelper;
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
            // $filters = $request->only([
            //     'fecha_inicio',
            //     'fecha_final',
            //     'search'
            // ]);

            // $certificados = $this->certificadoService->getAllCertificados($filters);
            $certificados = $this->certificadoService->getAllCertificados();

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
                'codigo_tipocertificado',
                'id_sucursal',
                'id_programa',
                'id_modulo',
                'fecha_inicio',
                'fecha_final',
                'search'
            ]);

            $perPage = (int) $request->input('per_page', $request->input('limit', 10));

            $certificados = $this->certificadoService->getAllCertificadosWithFilters($filters, $perPage);

            if ($certificados->isEmpty()) {
                return response()->json([
                    'result' => false,
                    'data' => [],
                    'message' => 'No se encontraron resultados'
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
        return $this->servePdfResponse($id, 'attachment');

        // try {
        //     $fileData = $this->certificadoService->downloadCertificado($id);

        //     return response()->download($fileData['full_path'], $fileData['filename'], [
        //         'Content-Type' => 'application/pdf',
        //     ]);
        // } catch (Exception $e) {
        //     $statusCode = $e->getCode() === 404 ? 404 : 500;

        //     Log::error("Error al descargar el certificado {$id}: " . $e->getMessage());

        //     return response()->json([
        //         'status'  => 'error',
        //         'message' => $e->getMessage()
        //     ], $statusCode);
        // }
    }

    public function downloadPDF(int $id): BinaryFileResponse|JsonResponse
    {
        return $this->servePdfResponse($id, 'inline');

        // try {
        //     $fileData = $this->certificadoService->downloadCertificado($id);

        //     return response()->file($fileData['full_path'], [
        //         'Content-Type'        => 'application/pdf',
        //         'Content-Disposition' => 'inline; filename="' . $fileData['filename'] . '"'
        //     ]);
        // } catch (Exception $e) {
        //     $statusCode = $e->getCode() === 404 ? 404 : 500;

        //     Log::error("Error al previsualizar el certificado {$id}: " . $e->getMessage());

        //     return response()->json([
        //         'status'  => 'error',
        //         'message' => $e->getMessage()
        //     ], $statusCode);
        // }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->all();

            $usuarioAutenticado = Auth::user();
            $username = $usuarioAutenticado ? ($usuarioAutenticado->name) : 'systemapi';
            $data['user_crea'] = $username;
            $data['fecha_crea'] = FechaHelper::obtenerFechaActual();

            $dto = CertificadoCreateDTO::from($data);

            // Intentar registrar certificado
            try {
                $certificado = $this->certificadoService->createCertificado($dto);
            } catch (Exception $e) {
                Log::error("Error al registrar el certificado: " . $e->getMessage());

                return response()->json([
                    'result' => false,
                    'message' => 'No se pudo crear el certificado: ' . $e->getMessage(),
                    'code' => 'CERTIFICADO_CREATE_ERROR'
                ]);
            }

            // Intentar generar el PDF y QR asociados al certificado guardado
            try {
                $this->certificadoService->generatePDF($certificado->id);
            } catch (Exception $e) {
                Log::error("Error al generar el PDF del certificado [ID: {$certificado->id}]: " . $e->getMessage(), [
                    'file'  => $e->getFile(),
                    'line'  => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);

                return response()->json([
                    'result'  => false,
                    'data'    => $certificado,
                    'message' => 'El certificado fue registrado correctamente, pero ocurrió un error al generar el archivo PDF.',
                    'code'    => 'PDF_GENERATION_ERROR'
                ], 500);
            }

            // Respuesta en caso de éxito total
            return response()->json([
                'result'  => true,
                'data'    => $certificado,
                'message' => 'Certificado y archivo PDF generados exitosamente.',
                'code'    => 'CORRECT_RECORDED'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'result'  => false,
                'message' => 'Error de validación en los datos enviados.',
                'errors'  => $e->errors(),
                'code'    => 'INVALID_RECORD'
            ], 422);
        } catch (Exception $e) {
            Log::error("Error inesperado en store Certificado: " . $e->getMessage());

            return response()->json([
                'result'  => false,
                'message' => 'Ocurrió un error inesperado al procesar la solicitud.',
                'code'    => 'SERVER_ERROR'
            ], 500);
        }
    }

    public function storeModular(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'id_persona'         => 'required|integer|exists:persona,id',
            'id_modulo'          => 'required|integer|exists:modulo,id',
            'id_plantilla'       => 'required|integer|exists:plantilla,id',
            'id_sucursal'     => 'required|integer|exists:institucion,id',
            'codigo_tipocertificado' => 'required|integer',
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

            Log::info('Información de certificado obtenido', ['certificado' => $certificado]);

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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->certificadoService->deleteCertificado($id);

            return response()->json([
                'result'  => true,
                'message' => 'Certificado y archivos asociados eliminados correctamente.'
            ], 200);
        } catch (Exception $e) {
            $statusCode = ($e->getCode() >= 400 && $e->getCode() < 600) ? $e->getCode() : 500;

            Log::error("Error al eliminar el certificado {$id}: " . $e->getMessage());

            return response()->json([
                'result'  => false,
                'message' => $e->getMessage()
            ], $statusCode);
        }
    }

    /**
     * Método privado helper para servir el PDF según la disposición solicitada.
     */
    private function servePdfResponse(int $id, string $disposition = 'attachment'): BinaryFileResponse|JsonResponse
    {
        try {
            $fileData = $this->certificadoService->downloadCertificado($id);

            return response()->download(
                $fileData['full_path'],
                $fileData['filename'],
                ['Content-Type' => 'application/pdf'],
                $disposition
            );
        } catch (Exception $e) {
            $statusCode = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;

            Log::error("Error procesando certificado ID {$id}: " . $e->getMessage());

            return response()->json([
                'result'  => false,
                'message' => $e->getMessage(),
            ], $statusCode);
        }
    }
}
