<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\InstitucionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
    public function index()
    {
        try {
            $instituciones = $this->institucionService->getAllInstituciones();

            return response()->json([
                'result' => true,
                'data' => $instituciones,
                'message' => $instituciones->isEmpty() ? 'No se encontraron instituciones' : 'Listado obtenido correctamente'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error en getFiltered personas: " . $e->getMessage());
            return response()->json([
                'result' => false,
                'message' => 'Error al procesar la solicitud',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
