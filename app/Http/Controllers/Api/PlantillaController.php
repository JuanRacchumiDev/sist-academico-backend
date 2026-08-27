<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Plantilla\PlantillaCreateDTO;
use App\DTOs\Plantilla\PlantillaUpdateDTO;
use App\Http\Controllers\Controller;
use App\Services\Contracts\IPlantillaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Helpers\FechaHelper;

class PlantillaController extends Controller
{
    protected IPlantillaService $plantillaService;

    public function __construct(IPlantillaService $plantillaService)
    {
        $this->plantillaService = $plantillaService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->query('per_page');
            $filters = $request->only(['id_institucion', 'search']);

            if ($perPage) {
                $plantillas = $this->plantillaService->getAllPlantillasWithFilters($filters, (int) $perPage);
            } else {
                $plantillas = $this->plantillaService->getAllPlantillas($filters);
            }

            return response()->json([
                'success' => true,
                'data'    => $plantillas,
                'message' => 'Listado de plantillas obtenido correctamente'
            ], 200);
        } catch (\Throwable $e) {
            Log::error("Error fetching plantillas: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener plantillas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $data = array_merge($request->all(), $request->allFiles());

            $usuarioAutenticado = Auth::user();
            $username = $usuarioAutenticado ? ($usuarioAutenticado->name) : 'systemapi';
            $data['user_crea'] = $username;
            $data['fecha_crea'] = FechaHelper::obtenerFechaActual();

            if (!isset($data['estado'])) {
                $data['estado'] = true;
            }

            $dto = PlantillaCreateDTO::validateAndCreate($data);

            $plantilla = $this->plantillaService->createPlantilla($dto);

            return response()->json([
                'success' => true,
                'data'    => $plantilla,
                'message' => 'Plantilla creada exitosamente',
                'code' => 'CORRECT_RECORDED'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors'  => $e->errors(),
                'code' => 'INVALID_RECORD'
            ], 422);
        } catch (\Throwable $e) {
            Log::error("Error al crear la plantilla: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al crear la plantilla: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            Log::error("Error creating plantilla: " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al crear plantilla: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $plantilla = $this->plantillaService->getPlantillaById($id);

            if (!$plantilla) {
                return response()->json([
                    'success' => false,
                    'message' => 'Plantilla no encontrada'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data'    => $plantilla,
                'message' => 'Plantilla encontrada correctamente'
            ], 200);
        } catch (\Throwable $e) {
            Log::error("Error fetching plantilla {$id}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la plantilla: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $dto = PlantillaUpdateDTO::validateAndCreate($request->all());

            $plantilla = $this->plantillaService->updatePlantilla($id, $dto);

            if (!$plantilla) {
                return response()->json([
                    'success' => false,
                    'message' => 'Plantilla no encontrada'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data'    => $plantilla,
                'message' => 'Plantilla actualizada exitosamente'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            Log::error("Error al actualizar la plantilla {$id}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la plantilla: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->plantillaService->deletePlantilla($id);

            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Plantilla no encontrada o no se pudo eliminar'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Plantilla eliminada exitosamente'
            ], 200);
        } catch (\Throwable $e) {
            Log::error("Error al eliminar la plantilla {$id}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la plantilla: ' . $e->getMessage()
            ], 500);
        }
    }
}
