<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Parametro\ParametroCreateDTO;
use App\DTOs\Parametro\ParametroUpdateDTO;
use App\Helpers\FechaHelper;
use App\Http\Controllers\Controller;
use App\Services\Contracts\IParametroService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ParametroController extends Controller
{
    protected IParametroService $parametroService;

    public function __construct(IParametroService $parametroService)
    {
        $this->parametroService = $parametroService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $parametros = $this->parametroService->getAllParametros();

            if ($parametros->isEmpty()) {
                return response()->json([
                    'succes' => true,
                    'data' => [],
                    'message' => 'No se encontraron parámetros'
                ], 200);
            }

            return response()->json([
                'success' => true,
                'data' => $parametros,
                'message' => 'Listado de parámetros correctos'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching parámetros: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener parámetros: ' . $e->getMessage()
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

            if (isset($data['nombre'])) {
                $data['nombre_url'] = Str::slug($data['nombre']);
            }

            $usuarioAutenticado = Auth::user();
            $username = $usuarioAutenticado ? ($usuarioAutenticado->name) : 'systemapi';
            $data['user_crea'] = $username;

            // Obteniendo la fecha actual
            $data['fecha_crea'] = FechaHelper::obtenerFechaActual();

            $parametroCreateDTO = ParametroCreateDTO::from($data);

            $parametro = $this->parametroService->createParametro($parametroCreateDTO);

            return response()->json([
                'success' => true,
                'data' => $parametro,
                'message' => 'Parámetro registrado correctamente'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error("Error creating parametro: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al crear parámetro : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $clase): JsonResponse
    {
        try {
            $parametro = $this->parametroService->getParametroByClase($clase);

            if (!$parametro) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parámetro no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $parametro,
                'message' => 'Parámetro encontrado correctamente'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching parámetro (clase: {$clase}): " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el parámetro: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $clase, Request $request): JsonResponse
    {
        try {
            $data = $request->all();

            $usuarioAutenticado = Auth::user();
            $username = $usuarioAutenticado ? ($usuarioAutenticado->name) : 'systemapi';
            $data['user_actualiza'] = $username;

            // Obteniendo fecha actual
            $data['fecha_actualiza'] = FechaHelper::obtenerFechaActual();

            $parametroUpdateDTO = ParametroUpdateDTO::from();

            $parametro = $this->parametroService->updateParametro($clase, $parametroUpdateDTO);

            if (!$parametro) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parámetro no encontrado o no se pudo actualizar'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $parametro,
                'message' => 'Parámetro actualizado correctamente'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error("Error updating parámetro (clase: {$clase}): " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el parámetro : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $clase): Response|JsonResponse
    {
        try {
            if (!$this->parametroService->deleteParametro($clase)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parámetro no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Parámetro eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error al eliminar el parámetro (clase: {$clase}): " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el parámetro: ' . $e->getMessage()
            ], 500);
        }
    }
}
