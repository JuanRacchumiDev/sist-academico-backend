<?php

namespace App\Http\Controllers\Api;

use App\DTOs\DetalleParametro\DetalleParametroCreateDTO;
use App\DTOs\DetalleParametro\DetalleParametroUpdateDTO;
use App\Http\Controllers\Controller;
use App\Services\Contracts\IDetalleParametroService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class DetalleParametroController extends Controller
{
    protected IDetalleParametroService $detalleParametroService;

    public function __construct(IDetalleParametroService $detalleParametroService)
    {
        $this->detalleParametroService = $detalleParametroService;
    }

    /**
     * Obtiene una lista de DetalleParametro filtrados
     * @param Request $request
     * @return JsonResponse
     */
    public function getFiltered(Request $request): JsonResponse
    {
        try {
            // $request->validate([
            //     'parametro_clase' => 'sometimes|integer|array',
            //     'parametro_clase.*' => 'integer|exists:parametro,clase',
            //     'en_persona' => 'sometimes|boolean',
            //     'en_empresa' => 'sometimes|boolean',
            //     'visible' => 'sometimes|boolean',
            //     'estado' => 'sometimes|boolean'
            // ]);

            $filters = $request->only(['parametro_clase', 'en_persona', 'en_empresa', 'visible', 'estado']);

            $filters = array_map(function($value) {
                if ($value === 'true') return true;
                if ($value === 'false') return false;
                return $value;
            }, $filters);

            $detalleParametros = $this->detalleParametroService->getAllFiltered($filters);

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
            Log::error("Error filtering catálogos: " . $e->getMessage());
            
            return response()->json([
                'result' => false,
                'message' => 'Error al obtener catálogos filtrados.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(string $clase): JsonResponse
    {
        try {
            $paramClase = config('params.clases.'.$clase);

            if (is_null($paramClase)) {
                return response()->json([
                    'result' => false,
                    'message' => 'Clase de parámetro no válido'
                ], 404);
            }

            $detalles = $this->detalleParametroService->getAllByClase($paramClase);

            if ($detalles->isEmpty()) {
                return response()->json([
                    'result' => true,
                    'data' => [],
                    'message' => 'No se encontraron registros para la clase de parámetro'
                ], 200);
            }

            return response()->json([
                'result' => true,
                'data' => $detalles,
                'message' => 'Listado de registros obtenido correctamente'
            ], 200);

        } catch (\Exception $e) {
            Log::error("Error fetching detalles para la clase '{$clase}': " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al obtener los registros: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(string $clase, Request $request): JsonResponse
    {
        try {
            $paramClase = config('params.clases.'.$clase);

            if (is_null($paramClase)) {
                return response()->json([
                    'result' => false,
                    'message' => 'Clase de parámetro no válida'
                ], 404);
            }

            $data = $request->all();
            
            $data['parametro_clase'] = $paramClase;

            if (isset($data['nombre'])) {
                $data['nombre_url'] = Str::slug($data['nombre']);
            }

            $detalleCreateDTO = DetalleParametroCreateDTO::from($data);

            $detalle = $this->detalleParametroService->createDetalle((object)$detalleCreateDTO);

            return response()->json([
                'result' => true,
                'data' => $detalle,
                'message' => 'Registro creado exitosamente'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'result' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error("Error al crear el registro '{$clase}': " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al crear el registro: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $clase, string $codigo): JsonResponse
    {
        try {
            $paramClase = config('params.clases.'.$clase);

            $detalle = $this->detalleParametroService->getByClaseAndCodigo($paramClase, (int)$codigo);
            
            if (!$detalle) {
                return response()->json([
                    'result' => false,
                    'message' => 'Detalle no encontrado',
                    'data' => []
                ], 404);
            }
            
            return response()->json([
                'result' => true,
                'data' => $detalle,
                'message' => 'Detalle encontrado correctamente'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching detalle (codigo: {$codigo}): " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al obtener el detalle: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $clase, string $codigo, Request $request): JsonResponse
    {
        try {
            $paramClase = config('params.clases.'.$clase);

            $data = $request->all();
            $data['clase'] = $paramClase;

            // Usamos el DTO para manejar la validación y mapear la data
            $detalleUpdateDTO = DetalleParametroUpdateDTO::from($data);
            
            $detalle = $this->detalleParametroService->updateDetalle((int)$codigo, $detalleUpdateDTO);
            
            if (!$detalle) {
                return response()->json([
                    'result' => false,
                    'data' => [],
                    'message' => 'Detalle no encontrado o no se pudo actualizar'
                ], 404);
            }
            
            return response()->json([
                'result' => true,
                'data' => $detalle,
                'message' => 'Detalle actualizado correctamente'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'result' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            $message = 'Error al actualizar el detalle:' . $e->getMessage();

            Log::error("Error updating detalle (codigo: {$codigo}): " . $e->getMessage());
            
            return response()->json([
                'result' => false,
                'message' => $message
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    //     //
    // }
}
