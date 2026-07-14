<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Modulo\ModuloCreateDTO;
use App\DTOs\Modulo\ModuloUpdateDTO;
use App\Http\Controllers\Controller;
use App\Services\Contracts\IModuloService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class ModuloController extends Controller
{
    protected IModuloService $moduloService;

    public function __construct(IModuloService $moduloService)
    {
        $this->moduloService = $moduloService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $modulos = $this->moduloService->getAllModulos();

            if ($modulos->isEmpty()) {
                return response()->json([
                    'result' => true,
                    'data' => [],
                    'message' => 'No se encontraron módulos'
                ], 200);
            }

            return response()->json([
                'result' => true,
                'data' => $modulos,
                'message' => 'Listado de módulos correctos'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching módulos: " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al obtener módulos: ' . $e->getMessage()
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

            $modulos = $this->moduloService->getAllModulosWithFilters($filters, $perPage);

            if ($modulos->isEmpty()) {
                return response()->json([
                    'result' => false,
                    'data' => [],
                    'message' => 'No se encontraron resultados'
                ], 200);
            }

            return response()->json([
                'result' => true,
                'data' => $modulos,
                'message' => 'Resultados encontrados correctamente',
                'pagination' => [
                    'total' => $modulos->total(),
                    'per_page' => $modulos->perPage(),
                    'current_page' => $modulos->currentPage(),
                    'last_page' => $modulos->lastPage(),
                    'from' => $modulos->firstItem(),
                    'to' => $modulos->lastItem()
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error filtering módulos: " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al obtener módulos.',
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
            $request->validate([
                'id_programa' => 'required|exists:programa,id',
                'modulos' => 'required|array|min:1',
            ]);

            $idPrograma = $request->input('id_programa');
            $modulosInput = $request->input('modulos');

            $usuarioAutenticado = Auth::user();
            $username = $usuarioAutenticado ? ($usuarioAutenticado->name) : 'systemapi';

            $dtos = array_map(function ($item) use ($idPrograma, $username) {
                $item['id_programa'] = $idPrograma;
                $item['titulo_url'] = Str::slug($item['titulo']);
                $item['estado'] = $item['estado'] ?? true;
                // Asignamos un orden temporal para pasar la validación si fuera necesaria
                $item['orden'] = $item['orden'] ?? 0;
                $item['temario'] = $item['temario'] ?? null;
                $item['user_crea'] = $username;

                return ModuloCreateDTO::from($item);
            }, $modulosInput);

            $creados = $this->moduloService->createModulosBatch($idPrograma, $dtos);

            return response()->json([
                'result' => true,
                'data' => $creados,
                'message' => count($creados) . ' módulos registrados correctamente'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'result' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error("Error creating módulo: " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al crear módulo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $modulo = $this->moduloService->getModuloById($id);

            if (!$modulo) {
                return response()->json([
                    'result' => false,
                    'message' => 'Módulo no encontrado',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'result' => true,
                'data' => $modulo,
                'message' => 'Módulo encontrado correctamente'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching módulo (id: {$id}): " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al obtener el módulo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $data = $request->all();

            if (isset($data['titulo'])) {
                $data['titulo_url'] = Str::slug($data['titulo']);
            }

            $usuarioAutenticado = Auth::user();
            $username = $usuarioAutenticado ? $usuarioAutenticado->name : 'systemapi';
            $data['user_actualiza'] = $username;

            $moduloUpdateDTO = ModuloUpdateDTO::from([
                ...$data,
                'id' => (int)$id
            ]);

            $modulo = $this->moduloService->updateModulo((int)$id, $moduloUpdateDTO);

            if (!$modulo) {
                return response()->json([
                    'result' => false,
                    'data' => [],
                    'message' => 'Módulo no encontrado o no se pudo actualizar'
                ], 404);
            }

            return response()->json([
                'result' => true,
                'data' => $modulo,
                'message' => 'Módulo actualizado correctamente'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'result' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error("Error actualizando módulo ID {$id}: " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al actualizar el módulo: ' . $e->getMessage()
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
