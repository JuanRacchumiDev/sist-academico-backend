<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Programa\ProgramaCreateDTO;
use App\Http\Controllers\Controller;
use App\Models\Programa;
use App\Services\Contracts\IProgramaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProgramaController extends Controller
{
    protected IProgramaService $programaService;

    public function __construct(IProgramaService $programaService)
    {
        $this->programaService = $programaService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $programas = $this->programaService->getAllProgramas();

            if ($programas->isEmpty()) {
                return response()->json([
                    'result' => true,
                    'data' => [],
                    'message' => 'No se encontraron programas'
                ], 200);
            }

            return response()->json([
                'result' => true,
                'data' => $programas,
                'message' => 'Listado de programas correctos'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching programas: ' . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al obtener programas: ' . $e->getMessage()
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

            $filters = array_map(function($value) {
                if ($value === 'true') return true;
                if ($value === 'false') return false;
                return $value;
            }, $filters);

            $programas = $this->programaService->getAllProgramasWithFilters($filters, $perPage);

            if ($programas->isEmpty()) {
                return response()->json([
                    'result' => false,
                    'data' => [],
                    'message' => 'No se encontraron resultados'
                ], 200);
            }

            return response()->json([
                'result' => true,
                'data' => $programas,
                'message' => 'Resultados encontrados correctamente',
                'pagination' => [
                    'total' => $programas->total(),
                    'per_page' => $programas->perPage(),
                    'current_page' => $programas->currentPage(),
                    'last_page' => $programas->lastPage(),
                    'from' => $programas->firstItem(),
                    'to' => $programas->lastItem()
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error filtering programas: " . $e->getMessage());
            
            return response()->json([
                'result' => false,
                'message' => 'Error al obtener programas.',
                'error' => $e->getMessage()
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

            if (isset($data['titulo'])) {
                $data['titulo_url'] = Str::slug($data['titulo']);
            }

            $programaCreateDTO = ProgramaCreateDTO::from($data);

            $programa = $this->programaService->createPrograma($programaCreateDTO);

            return response()->json([
                'result' => true,
                'message' => 'Programa creado exitosamente',
                'data' => $programa
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'result' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error("Error creating programa: " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al crear programa: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $programa = $this->programaService->getProgramaById($id);
            
            if (!$programa) {
                return response()->json([
                    'result' => false,
                    'message' => 'Programa no encontrado',
                    'data' => []
                ], 404);
            }
            
            return response()->json([
                'result' => true,
                'data' => $programa,
                'message' => 'Programa encontrado correctamente'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching programa (id: {$id}): " . $e->getMessage());
            
            return response()->json([
                'result' => false,
                'message' => 'Error al obtener el programa: ' . $e->getMessage()
            ], 500);
        }
    }
}
