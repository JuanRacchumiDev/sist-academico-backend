<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Matricula\MatriculaCreateDTO;
use App\DTOs\Matricula\MatriculaUpdateDTO;
use App\Http\Controllers\Controller;
use App\Models\Matricula;
use App\Services\Contracts\IMatriculaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class MatriculaController extends Controller
{
    protected IMatriculaService $matriculaService;

    public function __construct(IMatriculaService $matriculaService)
    {
        $this->matriculaService = $matriculaService;
    }

    public function index(): JsonResponse
    {
        try {
            $matriculas = $this->matriculaService->getAllMatriculas();

            if ($matriculas->isEmpty()) {
                return response()->json([
                    'result' => true,
                    'data' => [],
                    'message' => 'No se encontraron matrículas'
                ], 200);
            }

            return response()->json([
                'result' => true,
                'data' => $matriculas,
                'message' => 'Listado de matrículas correctas'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching matrículas: ' . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al obtener matrículas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getFilteredPaginate(Request $request): JsonResponse
    {
        try {
            $filters = [];

            if ($request->has('search')) {
                $filters['search'] = $request->input('search');
            }

            $perPage = $request->input('per_page', 10);

            $filters = array_map(function ($value) {
                if ($value === 'true') return true;
                if ($value === 'false') return false;
                return $value;
            }, $filters);

            $matriculas = $this->matriculaService->getAllMatriculasWithFilters($filters, $perPage);

            if ($matriculas->isEmpty()) {
                return response()->json([
                    'result' => false,
                    'data' => [],
                    'message' => 'No se encontraron resultados'
                ], 200);
            }

            return response()->json([
                'result' => true,
                'data' => $matriculas,
                'message' => 'Resultados encontrados correctamente',
                'pagination' => [
                    'total' => $matriculas->total(),
                    'per_page' => $matriculas->perPage(),
                    'current_page' => $matriculas->currentPage(),
                    'last_page' => $matriculas->lastPage(),
                    'from' => $matriculas->firstItem(),
                    'to' => $matriculas->lastItem()
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error filtering matrículas: " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al obtener matrículas.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function downloadFicha(int $id)
    {
        try {
            $path = $this->matriculaService->generateFichaPDF($id);

            // Retornar el archivo para visualizar en el navegador
            return response()->file($path, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline, filename="ficha_matricula.pdf"'
            ]);

            // return $pdf->stream("ficha_matricula_${id}.pdf");
        } catch (\Exception $e) {
            Log::error("Error al obtener PDF: " . $e->getMessage());
            return response()->json(['message' => 'Error al procesar el archivo'], 500);
        }
    }

    public function downloadCertificado(Request $request)
    {
        try {
            $request->validate([
                'id_matricula' => 'required|integer',
                'id_programa'  => 'required|integer'
            ]);

            $idMatricula = (int) $request->query('id_matricula');
            $idPrograma  = (int) $request->query('id_programa');

            $pdfContent = $this->matriculaService->generateCertificadoPDF($idMatricula, $idPrograma);

            $filename = "certificado_mat_{$idMatricula}_prog_{$idPrograma}.pdf";

            return new Response($pdfContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]);
        } catch (\Exception $e) {
            Log::error("Error al generar certificado: " . $e->getMessage());
            return response()->json([
                'result' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function regenerateFicha(int $id)
    {
        try {
            // Eliminar la ficha actual si existe
            $this->matriculaService->deleteFichaPDF($id);

            // Generar la nueva y obtener su ruta
            $path = $this->matriculaService->generateFichaPDF($id);

            return response()->file($path);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al regenerar'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->all();

            $filters = [
                'id_persona' => $data['id_persona'],
                'fecha_matricula' => $data['fecha_matricula']
            ];

            $matriculaExistente = $this->matriculaService->getAllMatriculas($filters)->first();

            if ($matriculaExistente) {
                return response()->json([
                    'result' => true,
                    'data' => $matriculaExistente->load('detalles'),
                    'message' => 'La matrícula ya ha sido ingresada',
                    'code' => 'PREVIOUSLY_REGISTERED'
                ], 200);
            }

            $data['id_formapago'] = $data['id_formapago_matricula'];
            $data['concepto_pago'] = "PAGO DE MATRÍCULA";

            $matriculaCreateDTO = MatriculaCreateDTO::from($data);

            $matricula = $this->matriculaService->createMatricula($matriculaCreateDTO);

            return response()->json([
                'result' => true,
                'data' => $matricula,
                'message' => 'Matrícula registrada correctamente',
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
            Log::error("Error al crear el registro de persona: " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al crear el registro: ' . $e->getMessage(),
                'code' => 'INVALID_RECORD'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $matricula = $this->matriculaService->getMatriculaById((int)$id);

            if (!$matricula) {
                return response()->json([
                    'result' => false,
                    'message' => 'Matrícula no encontrada',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'result' => true,
                'data' => $matricula,
                'message' => 'Matrícula encontrada correctamente'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching matrícula (id: {$id}): " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al obtener la matrícula: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(int $id, MatriculaUpdateDTO $dto): JsonResponse
    {
        try {
            $matricula = $this->matriculaService->updateMatricula($id, $dto);

            return response()->json([
                'message' => 'Matrícula actualizada correctamente',
                'data' => $matricula
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error actualizando matrícula ID {$id}: " . $e->getMessage());
            return response()->json([
                'message' => 'Error al procesar la actualización',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
