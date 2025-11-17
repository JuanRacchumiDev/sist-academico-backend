<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Plantilla\PlantillaCreateDTO;
use App\Http\Controllers\Controller;
use App\Services\Contracts\IPlantillaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

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
    public function index()
    {
        try {
            $plantillas = $this->plantillaService->getAllPlantillas();

            if ($plantillas->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'No se encontraron plantillas'
                ], 200);
            }

            return response()->json([
                'success' => true,
                'data' => $plantillas,
                'message' => 'Listado de plantillas correctos'
            ]);
        } catch (\Exception $e) {
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
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string:max:100',
                'descripcion' => 'nullable|string|max:150',
                'path' => 'required|file|mimes:pdf|max:2048',
                'estado' => 'boolean'
            ]);

            $data = PlantillaCreateDTO::from([
                ...$request->all(),
                'path' => $request->file('path')
            ]);

            $plantilla = $this->plantillaService->createPlantilla($data);

            return response()->json([
                'message' => 'Plantilla creada exitosamente',
                'data' => $plantilla,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'result' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error("Error al crear la plantilla: " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al crear la plantilla: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
                'data' => $plantilla,
                'message' => 'Plantilla encontrada correctamente'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching plantilla: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la plantilla: ' . $e->getMessage()
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
