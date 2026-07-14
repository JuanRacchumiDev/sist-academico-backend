<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Cuestionario\CuestionarioCreateDTO;
use App\DTOs\Cuestionario\CuestionarioUpdateDTO;
use App\Http\Controllers\Controller;
use App\Services\Contracts\ICuestionarioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class CuestionarioController extends Controller
{
    protected ICuestionarioService $cuestionarioService;

    public function __construct(ICuestionarioService $cuestionarioService)
    {
        $this->cuestionarioService = $cuestionarioService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $cuestionarios = $this->cuestionarioService->getAllCuestionarios();

            if ($cuestionarios->isEmpty()) {
                return response()->json([
                    'result' => true,
                    'data' => [],
                    'message' => 'No se encontraron cuestionarios'
                ], 200);
            }

            return response()->json([
                'result' => true,
                'data' => $cuestionarios,
                'message' => 'Listado de cuestionarios correctos'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching cuestionarios: ' . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al obtener cuestionarios: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getFilteredPaginate(Request $request): JsonResponse
    {
        try {
            $filters = $request->only([
                'id_programa',
                'id_modulo',
                'titulo'
            ]);

            $perPage = $request->input('per_page', 10);

            $cuestionarios = $this->cuestionarioService->getAllCuestionariosWithFilters($filters, $perPage);

            if ($cuestionarios->isEmpty()) {
                return response()->json([
                    'result' => false,
                    'data' => [],
                    'message' => 'No se encontraron resultados'
                ], 200);
            }

            return response()->json([
                'result' => true,
                'data' => $cuestionarios,
                'message' => 'Resultados encontrados correctamente',
                'pagination' => [
                    'total' => $cuestionarios->total(),
                    'per_page' => $cuestionarios->perPage(),
                    'current_page' => $cuestionarios->currentPage(),
                    'last_page' => $cuestionarios->lastPage(),
                    'from' => $cuestionarios->firstItem(),
                    'to' => $cuestionarios->lastItem()
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error filtering cuestionarios: " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al obtener cuestionarios.',
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
                'id_programa' => $data['id_programa'],
                'id_modulo' => $data['id_modulo'],
                'titulo' => $data['titulo']
            ];

            $cuestionarioExistente = $this->cuestionarioService->getAllCuestionarios($filters)->first();

            if ($cuestionarioExistente) {
                return response()->json([
                    'result' => true,
                    'data' => $cuestionarioExistente,
                    'message' => 'El cuestionario ingresado se encuentra registrado',
                    'code' => 'PREVIOUSLY_REGISTERED'
                ], 200);
            }

            $usuarioAutenticado = Auth::user();
            $username = $usuarioAutenticado ? ($usuarioAutenticado->name) : 'systemapi';
            $data['user_crea'] = $username;

            $cuestionarioCreateDTO = CuestionarioCreateDTO::from($data);

            $cuestionario = $this->cuestionarioService->createCuestionario($cuestionarioCreateDTO);

            return response()->json([
                'result' => true,
                'data' => $cuestionario,
                'message' => 'Cuestionario creado exitosamente',
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
            Log::error("Error creating cuestionario: " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al crear cuestionario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $cuestionario = $this->cuestionarioService->getCuestionarioById($id);

            if (!$cuestionario) {
                return response()->json([
                    'result' => false,
                    'message' => 'Cuestionario no encontrado',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'result' => true,
                'data' => $cuestionario,
                'message' => 'Cuestionario encontrado correctamente'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching cuestionario (id: {$id}): " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al obtener el cuestionario: ' . $e->getMessage()
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

            $cuestionarioUpdateDTO = CuestionarioUpdateDTO::from([
                ...$data,
                'id' => $id
            ]);

            $cuestionario = $this->cuestionarioService->updateCuestionario($id, $cuestionarioUpdateDTO);

            if (!$cuestionario) {
                return response()->json([
                    'result' => false,
                    'data' => [],
                    'message' => 'Cuestionario no encontrado o no se pudo actualizar'
                ], 404);
            }

            return response()->json([
                'result' => true,
                'data' => $cuestionario,
                'message' => 'Cuestionario actualizado correctamente'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'result' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            $message = 'Error al actualizar el cuestionario:' . $e->getMessage();

            Log::error("Error updating cuestionario: " . $e->getMessage());

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
