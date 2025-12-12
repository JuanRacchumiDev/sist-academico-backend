<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Persona\PersonaCreateDTO;
use App\DTOs\Persona\PersonaUpdateDTO;
use App\Http\Controllers\Controller;
use App\Services\Contracts\IPersonaService;
use App\Services\Contracts\IPersonaAPIService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PersonaController extends Controller
{
    protected IPersonaService $personaService;
    protected IPersonaAPIService $personaAPIService;

    public function __construct(IPersonaService $personaService, IPersonaAPIService $personaAPIService)
    {
        $this->personaService = $personaService;
        $this->personaAPIService = $personaAPIService;
    }

    /**
     * Display a listing of the resource.
     */
    public function getAll(string $nombreGrupo): JsonResponse
    {
        try {
            $personas = $this->personaService->getAllPersonaByGrupo($nombreGrupo);

            if ($personas->isEmpty()) {
                return response()->json([
                    'result' => true,
                    'data' => [],
                    'message' => 'No se encontraron personas'
                ], 200);
            }

            return response()->json([
                'result' => true,
                'data' => $personas,
                'message' => 'Listado de personas correctos'
            ]);

        } catch (\Exception $e) {
            Log::error("Error fetching parámetros: " . $e->getMessage());
            
            return response()->json([
                'result' => false,
                'message' => 'Error al obtener parámetros: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getFilteredPaginate(Request $request, string $nombreGrupo): JsonResponse
    {
        try {
            $filters = [];

            if ($request->has('search')) {
                $filters['search'] = $request->input('search');
            }

            $perPage = $request->input('per_page', 10);

            $filters = array_map(function($value) {
                if ($value === 'true') return true;
                if ($value === 'false') return false;
                return $value;
            }, $filters);

            $personas = $this->personaService->getAllPersonaByGrupoFiltered(
                $nombreGrupo,
                $filters,
                $perPage
            );

            if ($personas->isEmpty()) {
                return response()->json([
                    'result' => false,
                    'data' => [],
                    'message' => 'No se encontraron resultados'
                ], 200);
            }

            return response()->json([
                'result' => true,
                'data' => $personas,
                'message' => 'Resultados encontrados correctamente',
                'pagination' => [
                    'total' => $personas->total(),
                    'per_page' => $personas->perPage(),
                    'current_page' => $personas->currentPage(),
                    'last_page' => $personas->lastPage(),
                    'from' => $personas->firstItem(),
                    'to' => $personas->lastItem()
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error filtering personas: " . $e->getMessage());
            
            return response()->json([
                'result' => false,
                'message' => 'Error al obtener personas filtradas.',
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

            // Validar si existe un alumno registrado
            $alumnoExiste = $this->personaService->getPersonaByIdTipoDocAndNumDoc(
                (int)$data['id_tipodocumento'],
                $data['numero_documento']
            );

            if ($alumnoExiste) {
                return response()->json([
                    'result' => true,
                    'data' => $alumnoExiste,
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

            $validationRules = PersonaUpdateDTO::rules($id);

            $validatedData = $request->validate($validationRules);

            $personaUpdateDTO = PersonaUpdateDTO::from($validatedData);

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
                'message' => 'Persona actualiza correctamente'
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
        //
    }
}
