<?php

namespace App\Http\Controllers\Api;

use App\DTOs\DetalleParametro\DetalleParametroCreateDTO;
use App\DTOs\DetalleParametro\DetalleParametroUpdateDTO;
use App\Helpers\FechaHelper;
use App\Http\Controllers\Controller;
use App\Services\Contracts\IDetalleParametroService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

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
            $filters = $request->only([
                'parametro_clase',
                'en_persona',
                'en_empresa',
                'visible',
                'estado'
            ]);

            $filters = array_map(function ($value) {
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
     * Obtiene una lista paginada de DetalleParametro aplicando filtros dinámicos
     * @param Request $request
     * @return JsonResponse
     */
    public function getFilteredPaginate(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(
                [
                    'parametro_clase',
                    'en_persona',
                    'en_empresa',
                    'visible',
                    'estado'
                ]
            );

            if ($request->has('search')) {
                $filters['search'] = $request->input('search');
            }

            $perPage = (int)$request->input('limit', 10);

            $filters = array_map(function ($value) {
                if ($value === 'true') return true;
                if ($value === 'false') return false;
                return $value;
            }, $filters);

            $detalleParametros = $this->detalleParametroService->getAllFilteredPaginate($filters, $perPage);

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
                'message' => 'Resultados encontrados correctamente',
                'pagination' => [
                    'totalItems' => $detalleParametros->total(),
                    'perPage' => $detalleParametros->perPage(),
                    'currentPage' => $detalleParametros->currentPage(),
                    'totalPages' => $detalleParametros->lastPage(),
                    'nextPage' => $detalleParametros->hasMorePages() ? $detalleParametros->currentPage() + 1 : null,
                    'previousPage' => $detalleParametros->currentPage() > 1 ? $detalleParametros->currentPage() - 1 : null,
                ]

            ], 200);
        } catch (\Exception $e) {
            Log::error("Error filtering catálogos paginados: " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al obtener catálogos filtrados.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Muestra el listado de detalles asociados a una clase de parámetro.
     * @param string $clase
     * @return JsonResponse
     */
    public function index(string $clase): JsonResponse
    {
        try {
            $paramClase = config('params.clases.' . $clase);

            if (is_null($paramClase)) {
                return response()->json([
                    'result' => false,
                    'message' => 'Clase de parámetro no válida'
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
     * Registra un nuevo DetalleParametro.
     * @param string $clase
     * @param Request $request
     * @return JsonResponse
     */
    public function store(string $clase, Request $request): JsonResponse
    {
        try {
            $paramClase = config('params.clases.' . $clase);

            if (is_null($paramClase)) {
                return response()->json([
                    'result' => false,
                    'message' => 'Clase de parámetro no válida',
                    'code' => 'INVALID_CLASS'
                ], 404);
            }

            $data = $request->all();
            $data['parametro_clase'] = $paramClase;
            $data['fecha_crea'] = FechaHelper::obtenerFechaActual();

            if (isset($data['nombre'])) {
                $data['nombre_url'] = Str::slug($data['nombre']);
            }

            // Validar si existe un registro previo por clase y nombre_url
            $detalleExiste = $this->detalleParametroService->getUniqueByParams($data);

            if ($detalleExiste) {
                return response()->json([
                    'result' => true,
                    'data' => $detalleExiste,
                    'message' => 'El nombre ingresado ya se encuentra registrado',
                    'code' => 'PREVIOUSLY_REGISTERED'
                ], 200);
            }

            $usuarioAutenticado = Auth::user();
            $username = $usuarioAutenticado ? ($usuarioAutenticado->name) : 'systemapi';
            $data['user_crea'] = $username;

            $detalleCreateDTO = DetalleParametroCreateDTO::from($data);

            $detalle = $this->detalleParametroService->createDetalle((object)$detalleCreateDTO);

            return response()->json([
                'result' => true,
                'data' => $detalle,
                'message' => 'Registro creado exitosamente',
                'code' => 'CORRECT_RECORDED'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'result' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors(),
                'code' => 'INVALID_RECORD'
            ], 422);
        } catch (\Exception $e) {
            Log::error("Error al crear el registro '{$clase}': " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al crear el registro: ' . $e->getMessage(),
                'code' => 'INVALID_RECORD'
            ], 500);
        }
    }

    public function showByParams(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(
                [
                    'parametro_clase',
                    'codigo',
                    'nombre_url'
                ]
            );

            $detalle = $this->detalleParametroService->getUniqueByParams($filters);

            if (!$detalle) {
                return response()->json([
                    'result' => false,
                    'message' => 'Detalle no encontrado',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'result' => true,
                'data' => $detalle,
                'message' => 'Detalle encontrado correctamente'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching detalle: " . $e->getMessage());

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
            $paramClase = config('params.clases.' . $clase);

            if (is_null($paramClase)) {
                return response()->json([
                    'result' => false,
                    'message' => 'Clase de parámetro no válida',
                    'code' => 'INVALID_CLASS'
                ], 404);
            }

            $data = $request->all();

            $data['parametro_clase'] = $paramClase;
            $data['fecha_actualiza'] = FechaHelper::obtenerFechaActual();

            if (isset($data['nombre'])) {
                $data['nombre_url'] = Str::slug($data['nombre']);
            }

            // Validando si existe un registro previo
            $detalleExiste = $this->detalleParametroService->getUniqueByParams($data);

            if ($detalleExiste) {
                return response()->json([
                    'result' => true,
                    'data' => $detalleExiste,
                    'message' => 'El nombre modificado se encuentra registrado',
                    'code' => 'PREVIOUSLY_REGISTERED'
                ], 200);
            }

            $usuarioAutenticado = Auth::user();
            $username = $usuarioAutenticado ? ($usuarioAutenticado->name) : 'systemapi';
            $data['user_actualiza'] = $username;

            // Usamos el DTO para manejar la validación y mapear la data
            $detalleUpdateDTO = DetalleParametroUpdateDTO::from($data);

            $detalle = $this->detalleParametroService->updateDetalle((int)$codigo, $detalleUpdateDTO);

            if (!$detalle) {
                return response()->json([
                    'result' => false,
                    'data' => [],
                    'message' => 'Detalle no encontrado o no se pudo actualizar',
                    'code' => 'REGISTER_NOT_FOUND'
                ], 404);
            }

            return response()->json([
                'result' => true,
                'data' => $detalle,
                'message' => 'Detalle actualizado correctamente',
                'code' => 'CORRECT_RECORDED'
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
}
