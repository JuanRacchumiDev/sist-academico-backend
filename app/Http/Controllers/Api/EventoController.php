<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Evento\EventoCreateDTO;
use App\DTOs\Evento\EventoUpdateDTO;
use App\Http\Controllers\Controller;
use App\Services\Contracts\IEventoService;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EventoController extends Controller
{
    protected IEventoService $eventoService;

    public function __construct(IEventoService $eventoService)
    {
        $this->eventoService = $eventoService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $eventos = $this->eventoService->getAllEventos();

            if ($eventos->isEmpty()) {
                return response()->json([
                    'result' => true,
                    'data' => [],
                    'message' => 'No se encontraron eventos'
                ], 200);
            }

            return response()->json([
                'result' => true,
                'data' => $eventos,
                'message' => 'Listado de eventos correctos'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching eventos: " . $e->getMessage());
            
            return response()->json([
                'result' => false,
                'message' => 'Error al obtener eventos: ' . $e->getMessage()
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

            if (isset($data['titulo'])) {
                $data['titulo_url'] = Str::slug($data['titulo']);
            }

            $eventoCreateDTO = EventoCreateDTO::from($data);
            
            $evento = $this->eventoService->createEvento($eventoCreateDTO);
            
            return response()->json([
                'result' => true,
                'data' => $evento,
                'message' => 'Evento registrado correctamente'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'result' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error("Error creating evento: " . $e->getMessage());
            
            return response()->json([
                'result' => false,
                'message' => 'Error al crear evento : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $evento = $this->eventoService->getEventoById($id);
            
            if (!$evento) {
                return response()->json([
                    'result' => false,
                    'message' => 'Evento no encontrado',
                    'data' => []
                ], 404);
            }
            
            return response()->json([
                'result' => true,
                'data' => $evento,
                'message' => 'Evento encontrado correctamente'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching evento (id: {$id}): " . $e->getMessage());
            
            return response()->json([
                'result' => false,
                'message' => 'Error al obtener el evento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id, Request $request): JsonResponse
    {
        try {
            $data = $request->all();

            $eventoUpdateDTO = EventoUpdateDTO::from($data);

            $evento = $this->eventoService->updateEvento((int)$id, $eventoUpdateDTO);

            if (!$evento) {
                return response()->json([
                    'result' => false,
                    'data' => [],
                    'message' => 'Evento no encontrado o no se puede actualizar'
                ], 404);
            }

            return response()->json([
                'result' => true,
                'data' => $evento,
                'message' => 'Evento actualizado correctamente'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'result' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            $message = 'Error al actualizar al evento:' . $e->getMessage();

            Log::error("Error updating evento: " . $e->getMessage());
            
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
