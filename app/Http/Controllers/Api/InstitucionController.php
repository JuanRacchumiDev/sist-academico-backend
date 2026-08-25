<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Institucion\InstitucionCreateDTO;
use App\DTOs\Institucion\InstitucionUpdateDTO;
use App\Helpers\FechaHelper;
use App\Http\Controllers\Controller;
use App\Services\InstitucionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class InstitucionController extends Controller
{
    protected InstitucionService $institucionService;

    public function __construct(InstitucionService $institucionService)
    {
        $this->institucionService = $institucionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $instituciones = $this->institucionService->getAll();

            if ($instituciones->isEmpty()) {
                return response()->json([
                    'result' => true,
                    'data' => [],
                    'message' => 'No se encontraron instituciones'
                ], 200);
            }

            return response()->json([
                'result' => true,
                'data' => $instituciones,
                'message' => 'Listado de instituciones correctos'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error en getFiltered instituciones: " . $e->getMessage());
            return response()->json([
                'result' => false,
                'message' => 'Error al obtener instituciones: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene una lista de Instituciones filtrados
     * @param Request $request
     * @return JsonResponse
     */
    public function getFiltered(Request $request): JsonResponse
    {
        try {
            $filters = $request->only([
                'codigo_sede',
                'nombre',
                'is_cliente',
                'estado'
            ]);

            $filters = array_map(function ($value) {
                if ($value === 'true') return true;
                if ($value === 'false') return false;
                return $value;
            }, $filters);

            $detalleParametros = $this->institucionService->getAllFiltered($filters);

            if ($detalleParametros->isEmpty()) {
                return response()->json([
                    'result' => false,
                    'data' => [],
                    'message' => 'No se encontraron resultados'
                ], 200);
            }

            return response()->json([
                'result' => true,
                'data' => $detalleParametros,
                'message' => 'Resultados encontrados correctamente'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error filtering instituciones: " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al obtener instituciones filtrados.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getFilteredPaginate(Request $request): JsonResponse
    {
        try {
            $filters = $request->only([
                'codigo_sede',
                'is_cliente',
                'estado'
            ]);

            if ($request->has('search')) {
                $filters['search'] = $request->input('search');
            }

            $perPage = $request->input('per_page', 10);

            $instituciones = $this->institucionService->getAllFilteredPaginate($filters, $perPage);

            if ($instituciones->isEmpty()) {
                return response()->json([
                    'result' => false,
                    'data' => [],
                    'message' => 'No se encontraron resultados'
                ], 200);
            }

            return response()->json([
                'result' => true,
                'data' => $instituciones,
                'message' => 'Resultados encontrados correctamente',
                'pagination' => [
                    'total' => $instituciones->total(),
                    'per_page' => $instituciones->perPage(),
                    'current_page' => $instituciones->currentPage(),
                    'last_page' => $instituciones->lastPage(),
                    'from' => $instituciones->firstItem(),
                    'to' => $instituciones->lastItem()
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error filtering instituciones: " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al obtener instituciones.',
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
                'codigo_sede' => $data['codigo_sede'] ?? null,
                'nombre' => $data['nombre'] ?? ""
            ];

            $institucionExistente = $this->institucionService->getAllFiltered($filters)->first();

            if ($institucionExistente) {
                return response()->json([
                    'result' => true,
                    'data' => $institucionExistente,
                    'message' => 'La institución ingresada se encuentra registrada',
                    'code' => 'PREVIOUSLY_REGISTERED'
                ], 200);
            }

            $usuarioAutenticado = Auth::user();
            $username = $usuarioAutenticado ? ($usuarioAutenticado->name) : 'systemapi';
            $data['user_crea'] = $username;
            $data['fecha_crea'] = FechaHelper::obtenerFechaActual();

            $institucionCreateDTO = InstitucionCreateDTO::from($data);

            $institucion = $this->institucionService->createInstitucion($institucionCreateDTO);

            return response()->json([
                'result' => true,
                'data' => $institucion,
                'message' => 'Institución creada exitosamente',
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
            Log::error("Error creating institución: " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al crear institución: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $institucion = $this->institucionService->getInstitucionById($id);

            if (!$institucion) {
                return response()->json([
                    'result' => false,
                    'message' => 'Institución no encontrada',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'result' => true,
                'data' => $institucion,
                'message' => 'Institución encontrada correctamente'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching institución (id: {$id}): " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al obtener el institución: ' . $e->getMessage()
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

            $usuarioAutenticado = Auth::user();
            $username = $usuarioAutenticado ? ($usuarioAutenticado->name) : 'systemapi';
            $data['user_actualiza'] = $username;
            $data['fecha_actualiza'] = FechaHelper::obtenerFechaActual();

            $institucionUpdateDTO = InstitucionUpdateDTO::from([
                ...$data,
                'id' => $id
            ]);

            $institucion = $this->institucionService->updateInstitucion($id, $institucionUpdateDTO);

            if (!$institucion) {
                return response()->json([
                    'result' => false,
                    'data' => [],
                    'message' => 'Institución no encontrada o no se pudo actualizar'
                ], 404);
            }

            return response()->json([
                'result' => true,
                'data' => $institucion,
                'message' => 'Institución actualizado correctamente'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'result' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            $message = 'Error al actualizar la institución:' . $e->getMessage();

            Log::error("Error updating institución: " . $e->getMessage());

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
        //
    }
}
