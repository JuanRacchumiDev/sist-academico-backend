<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Contracts\IPlantillaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PlantillaController extends Controller
{
    protected IPlantillaService $plantillaService;

    public function __construct(IPlantillaService $plantillaService)
    {
        $this->plantillaService = $plantillaService;
    }

    public function index(Request $request): JsonResponse
    {
        $rawFilters = $request->only([
            'id_institucion',
            'codigo_tipoprograma',
            'search',
            'id_sucursal'
        ]);

        $filtered = array_filter($rawFilters, fn($value) => !is_null($value) && $value !== '');

        $filters = empty($filtered) ? null : $filtered;

        try {
            $plantillas = $this->plantillaService->getAllPlantillas($filters);

            if ($plantillas->isEmpty()) {
                return response()->json([
                    'result' => true,
                    'data' => [],
                    'message' => 'No se encontraron plantillas'
                ], 200);
            }

            return response()->json([
                'result' => true,
                'data' => $plantillas,
                'message' => 'Listado de plantillas correctos'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching plantillas: ' . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al obtener plantillas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function getFilteredPaginate(Request $request): JsonResponse
    {
        try {
            $filters = $request->only([
                'id_institucion',
                'codigo_tipoprograma',
                'search',
                'id_sucursal'
            ]);

            $perPage = (int)$request->query('limit', 10);

            $plantillas = $this->plantillaService->getAllPlantillasWithFilters($filters, $perPage);

            if ($plantillas->isEmpty()) {
                return response()->json([
                    'result' => false,
                    'data' => [],
                    'message' => 'No se encontraron resultados'
                ], 200);
            }

            return response()->json([
                'result' => true,
                'data' => $plantillas,
                'message' => 'Resultados encontrados correctamente',
                'pagination' => [
                    'totalItems' => $plantillas->total(),
                    'perPage' => $plantillas->perPage(),
                    'currentPage' => $plantillas->currentPage(),
                    'totalPages' => $plantillas->lastPage(),
                    'nextPage' => $plantillas->hasMorePages() ? $plantillas->currentPage() + 1 : null,
                    'previousPage' => $plantillas->currentPage() > 1 ? $plantillas->currentPage() - 1 : null
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error filtering plantillas: " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al obtener plantillas',
                'error' => $e->getMessage()
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
                    'result' => false,
                    'message' => 'Plantilla no encontrada',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'result' => true,
                'data'    => $plantilla,
                'message' => 'Plantilla encontrada correctamente'
            ], 200);
        } catch (\Throwable $e) {
            Log::error("Error fetching plantilla {$id}: " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al obtener la plantilla: ' . $e->getMessage()
            ], 500);
        }
    }
}
