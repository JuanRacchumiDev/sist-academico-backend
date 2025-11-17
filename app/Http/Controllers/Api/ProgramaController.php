<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Programa\ProgramaCreateDTO;
use App\DTOs\Programa\ProgramaUpdateDTO;
use App\DTos\Programa\UploadProgramaDTO;
use App\Http\Controllers\Controller;
use App\Models\Programa;
use App\Services\Contracts\IProgramaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class ProgramaController extends Controller
{
    protected IProgramaService $programaService;

    public function __construct(IProgramaService $programaService)
    {
        $this->programaService = $programaService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $programas = $this->programaService->getAllProgramas();

            if ($programas->isEmpty()) {
                return response()->json([
                    'result' => true,
                    'data' => [],
                    'message' => 'No se encontraron programas'
                ], 200);
            }

            return response()->json([
                'result' => true,
                'data' => $programas,
                'message' => 'Listado de programas correctos'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetchinf programas: ' . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al obtener programas: ' . $e->getMessage()
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
                'id_segmento' => 'required|integer',
                'sigla' => 'required|string|max:10',
                'nombre' => 'required|string|max:100',
                'duracion' => 'required|string|max:20',
                'modulos' => 'required|integer',
                'creditos' => 'required|integer',
                'plan' => 'required|file|mimes:pdf|max:2048',
                'is_vigente' => 'boolean',
                'estado' => 'boolean'
            ]);

            $data = ProgramaCreateDTO::from([
                ...$request->all(),
                'plan' => $request->file('plan')
            ]);

            $programa = $this->programaService->createPrograma($data);

            return response()->json([
                'message' => 'Programa creado exitosamente',
                'data' => $programa
            ], 201);

            // // Validar el Request antes de DTOs
            // $validatedData = $request->validate(UploadProgramaDTO::rules());

            // // return response()->json([
            // //     'result' => false,
            // //     'validatedData' => $validatedData,
            // //     'file' => $request->file('plan_file'),
            // //     'message' => 'Programa no registrado'
            // // ], 422);

            // // Crear el DTO con el archivo
            // $uploadDTO = UploadProgramaDTO::fromRequest(
            //     $validatedData,
            //     $request->file('plan_file')
            // );

            // $programa = $this->programaService->createPrograma($uploadDTO);

            // return response()->json([
            //     'result' => true,
            //     'data' => $programa,
            //     'message' => 'Programa registrado exitosamente'
            // ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'result' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error("Error creating programa: " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al crear programa: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadPlan(Programa $programa) {
        $planPath = $programa->plan;

        if (!$planPath || !Storage::disk('public')->exists($planPath)) {
            return response()->json([
                'result' => false,
                'message' => 'El archivo del plan de estudios no fue encontrado'
            ], 404);
        }

        // El nombre del usuario que se le dará al usuario
        $fileName = $programa->sigla.'_plan.pdf';

        // Usando Storage::download para enviar el archivo al navegador
        return Storage::disk('public')->download($planPath, $fileName);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $programa = $this->programaService->getProgramaById($id);
            
            if (!$programa) {
                return response()->json([
                    'result' => false,
                    'message' => 'Programa no encontrado',
                    'data' => []
                ], 404);
            }
            
            return response()->json([
                'result' => true,
                'data' => $programa,
                'message' => 'Programa encontrado correctamente'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching programa (id: {$id}): " . $e->getMessage());
            
            return response()->json([
                'result' => false,
                'message' => 'Error al obtener el programa: ' . $e->getMessage()
            ], 500);
        }
    }
}
