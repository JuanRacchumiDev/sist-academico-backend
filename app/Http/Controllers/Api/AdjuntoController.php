<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\DTOs\Adjunto\AdjuntoCreateDTO;
use App\Services\AdjuntoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

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
            $filters = $request->only(['search', 'id_programa', 'id_modulo', 'id_institucion']);
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'id_programa' => 'required|integer|exists:programa,id',
                'titulo' => 'required|string|max:100',
                'file' => 'required|file|max:10240',
            ]);

            $data = $request->all();

            $filters = [
                'id_programa' => $data['id_programa'],
                'titulo' => $data['titulo']
            ];

            $adjuntoExistente = $this->adjuntoService->getAllAdjuntos($filters)->first();

            if ($adjuntoExistente) {
                return response()->json([
                    'result' => true,
                    'data' => $adjuntoExistente,
                    'message' => 'El adjunto ya ha sido ingresado',
                    'code' => 'PREVIOUSLY_REGISTERED'
                ], 200);
            }

            // $adjuntoCreateDTO = AdjuntoCreateDTO::from($data);

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
