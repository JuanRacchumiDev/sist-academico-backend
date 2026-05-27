<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Programa\ProgramaCreateDTO;
use App\DTOs\Programa\ProgramaUpdateDTO;
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
            // $filters = [];

            // if ($request->has('search')) {
            //     $filters['search'] = $request->input('search');
            // }

            // $filters = array_map(function($value) {
            //     if ($value === 'true') return true;
            //     if ($value === 'false') return false;
            //     return $value;
            // }, $filters);

            $filters = $request->only([
                'titulo', 
                'id_segmento', 
                'id_tipoprograma', 
                'id_categoriaprograma', 
                'modalidad'
            ]);

            $perPage = $request->input('per_page', 10);

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

            $filters = [
                'id_segmento' => $data['id_segmento'],
                'id_tipoprograma' => $data['id_tipoprograma'],
                'titulo' => $data['titulo']
            ];

            $programaExistente = $this->programaService->getAllProgramas($filters)->first();

            if ($programaExistente) {
                return response()->json([
                    'result' => true,
                    'data' => $programaExistente,
                    'message' => 'El programa ingresado se encuentra registrado',
                    'code' => 'PREVIOUSLY_REGISTERED'
                ], 200);
            }

            if (isset($data['titulo'])) {
                $data['titulo_url'] = Str::slug($data['titulo']);
            }

            $programaCreateDTO = ProgramaCreateDTO::from($data);

            $programa = $this->programaService->createPrograma($programaCreateDTO);

            return response()->json([
                'result' => true,
                'data' => $programa,
                'message' => 'Programa creado exitosamente',
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

    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $data = $request->all();

            if (isset($data['titulo'])) {
                $data['titulo_url'] = Str::slug($data['titulo']);
            }

            $programaUpdateDTO = ProgramaUpdateDTO::from([
                ...$data,
                'id' => $id
            ]);

            $programa = $this->programaService->updatePrograma($id, $programaUpdateDTO);

            if (!$programa) {
                return response()->json([
                    'result' => false,
                    'data' => [],
                    'message' => 'Programa no encontrado o no se pudo actualizar'
                ], 404);
            }

            return response()->json([
                'result' => true,
                'data' => $programa,
                'message' => 'Programa actualizados correctamente'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'result' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            $message = 'Error al actualizar el programa:' . $e->getMessage();

            Log::error("Error updating programa: " . $e->getMessage());
            
            return response()->json([
                'result' => false,
                'message' => $message
            ], 500);
        }
    }
}
