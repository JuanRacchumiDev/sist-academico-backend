<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DTOs\Matricula\MatriculaCreateDTO;
use App\Services\Contracts\IMatriculaService;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MatriculaController extends Controller
{
    protected IMatriculaService $matriculaService;

    public function __construct(IMatriculaService $matriculaService)
    {
        $this->matriculaService = $matriculaService;
    }

    /**
     * Display a listing of the resource.
     */
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
                'message' => 'Listado de matrículas correctos'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching matrículas: " . $e->getMessage());
            
            return response()->json([
                'result' => false,
                'message' => 'Error al obtener matrículas: ' . $e->getMessage()
            ], 500);
        }   
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();

            $matriculaCreateDTO = MatriculaCreateDTO::from($data);
            
            $matricula = $this->matriculaService->createMatricula($matriculaCreateDTO);
            
            return response()->json([
                'result' => true,
                'data' => $matricula,
                'message' => 'Matrícula registrada correctamente'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'result' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error("Error creating matrícula: " . $e->getMessage());
            
            return response()->json([
                'result' => false,
                'message' => 'Error al crear matrícula: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $matricula = $this->matriculaService->getMatriculaById($id);
            
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
