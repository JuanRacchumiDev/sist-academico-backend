<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Persona\PersonaCreateDTO;
use App\DTOs\Persona\PersonaUpdateDTO;
use App\Http\Controllers\Controller;
use App\Services\PersonaService;
use App\Services\PersonaAPIService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PersonaController extends Controller
{
    protected PersonaService $personaService;
    protected PersonaAPIService $personaAPIService;

    public function __construct(PersonaService $personaService, PersonaAPIService $personaAPIService)
    {
        $this->personaService = $personaService;
        $this->personaAPIService = $personaAPIService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['search', 'id_tipodocumento', 'estado', 'grupo']);
            $perPage = (int)$request->input('per_page', 10);

            $personas = $this->personaService->getAllPersonasWithFilters($filters, $perPage);

            return response()->json([
                'result' => true,
                'data' => $personas->items(),
                'message' => $personas->isEmpty() ? 'No se encontraron resultados' : 'Listado de personas correctos',
                'pagination' => [
                    'total' => $personas->total(),
                    'per_page' => $personas->perPage(),
                    'current_page' => $personas->currentPage(),
                    'last_page' => $personas->lastPage()
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error("Error fetching personas: " . $e->getMessage());
            
            return response()->json([
                'result' => false,
                'message' => 'Error al obtener personas: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->all();

            $filters = [
                'id_tipodocumento' => $data['id_tipodocumento'],
                'numero_documento' => $data['numero_documento']
            ];

            $personaExistente = $this->personaService->getAllPersonas($filters)->first();

            if ($personaExistente) {
                return response()->json([
                    'result' => true,
                    'data' => $personaExistente,
                    'message' => 'El número de documento ingresado se encuentra registrado',
                    'code' => 'PREVIOUSLY_REGISTERED'
                ], 200);
            }

            $personaCreateDTO = PersonaCreateDTO::from($data);

            $persona = $this->personaService->createPersona($personaCreateDTO);

            return response()->json([
                'result' => true,
                'data' => $persona,
                'message' => 'Persona registrada correctamente',
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
            Log::error("Error al crear el registro de persona: " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al crear el registro: ' . $e->getMessage(),
                'code' => 'INVALID_RECORD'
            ], 500);
        }
    }

    public function storeApi(Request $request) {
        $request->validate([
            'tipo_documento' => 'required|string|max:10',
            'numero_documento' => 'required|string:max:15',
            'nombre_grupo' => 'required|string:max:50'
        ]);

        $data = $request->all();

        try {
            $persona = $this->personaAPIService->queryAndRegister(
                $data['tipo_documento'],
                $data['numero_documento'],
                $data['nombre_grupo']
            );

            return response()->json([
                'success' => true,
                'message' => 'Consulta de documento realizado con éxito',
                'data' => $persona
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al consultar documento',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function consultarDocumento(string $tipoDocumento, string $numeroDocumento)
    {
        $persona = $this->personaAPIService->query($tipoDocumento,$numeroDocumento);

        return response()->json([
            'success' => true,
            'message' => 'Documento consultado correctamente',
            'persona' => $persona
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $persona = $this->personaService->getPersonaById($id);

            if (!$persona) {
                return response()->json([
                    'result' => false,
                    'message' => 'Persona no encontrada',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'result' => true,
                'message' => 'Persona obtenida exitosamente',
                'data' => $persona
            ], 200);

        } catch (\Exception $e) {
            Log::error("Error fetching detalle persona: " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al obtener el detalle: ' . $e->getMessage()
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

            // Validando y transformado el DTO en un solo paso
            $personaUpdateDTO = PersonaUpdateDTO::from([
                ...$data,
                'id' => $id
            ]);

            $persona = $this->personaService->updatePersona($id, $personaUpdateDTO);

            if (!$persona) {
                return response()->json([
                    'result' => false,
                    'data' => [],
                    'message' => 'Persona no encontrada o no se pudo actualizar'
                ], 404);
            }

            return response()->json([
                'result' => true,
                'data' => $persona,
                'message' => 'Datos de persona actualizados correctamente'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'result' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            $message = 'Error al actualizar el detalle:' . $e->getMessage();

            Log::error("Error updating detalle persona: " . $e->getMessage());
            
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
        try {
            $deleted = $this->personaService->deletePersona($id);

            return response()->json([
                'result' => $deleted,
                'message' => $deleted ? 'Eliminado' : 'No encontrado'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'result' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
