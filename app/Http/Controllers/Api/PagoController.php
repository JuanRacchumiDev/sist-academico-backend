<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Contracts\IPagoService;
use App\DTOs\Pago\PagoCreateDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

class PagoController extends Controller
{
    protected IPagoService $pagoService;

    public function __construct(IPagoService $pagoService)
    {
        $this->pagoService = $pagoService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $pagos = $this->pagoService->getAllPagos();

            if ($pagos->isEmpty()) {
                return response()->json([
                    'result' => true,
                    'data' => [],
                    'message' => 'No se encontraron pagos'
                ], 200);
            }

            return response()->json([
                'result' => true,
                'data' => $pagos,
                'message' => 'Listado de pagos correctos'
            ]);
        } catch (\Exception $e) {
            Log::error("Error fetching eventos: " . $e->getMessage());
            
            return response()->json([
                'result' => false,
                'message' => 'Error al obtener eventos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getFilteredPaginate(Request $request): JsonResponse
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

            $pagos = $this->pagoService->getAllPagosWithFilters($filters, $perPage);

            if ($pagos->isEmpty()) {
                return response()->json([
                    'result' => false,
                    'data' => [],
                    'message' => 'No se encontraron resultados'
                ], 200);
            }

            return response()->json([
                'result' => true,
                'data' => $pagos,
                'message' => 'Resultados encontrados correctamente',
                'pagination' => [
                    'total' => $pagos->total(),
                    'per_page' => $pagos->perPage(),
                    'current_page' => $pagos->currentPage(),
                    'last_page' => $pagos->lastPage(),
                    'from' => $pagos->firstItem(),
                    'to' => $pagos->lastItem()
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error filtering pagos: " . $e->getMessage());
            
            return response()->json([
                'result' => false,
                'message' => 'Error al obtener pagos.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getMatricula(Request $request) {
        $request->validate([
            'id_alumno' => 'required|integer|exists:persona,id',
            'id_matricula' => 'required|integer|exists:matricula,id',
        ]);

        $idMatricula = $request->input('id_matricula');
        $idAlumno = $request->input('id_alumno');

        $filters = [
            'id_matricula' => $idMatricula,
            'id_alumno' => $idAlumno,
            'tipo' => 'matricula'
        ];

        // $result = $this->pagoService->getPagoMatriculaByFilters($filters);
        $result = $this->pagoService->getMatriculaPDF($filters);

        if ($result['status'] === 'error') {
            return response($result['message'], 404);
        }

        // Devolver el archivo PDF como respuesta binaria
        $filename = "pago_matricula_{$idMatricula}.pdf";

        return response($result['pdfContent'], 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "inline; filename=\"{$filename}\"");
    }

    public function getPagoModulo(Request $request) {
        $request->validate([
            'id_alumno' => 'required|integer|exists:persona,id',
            'id_matricula' => 'required|integer|exists:matricula,id',
            'numero_modulo' => 'required|integer'
        ]);

        $idAlumno = $request->input("id_alumno");
        $idMatricula = $request->input("id_matricula");
        $numeroModulo = $request->input("numero_modulo");

        $filters = [
            'id_alumno' => $idAlumno,
            'id_matricula' => $idMatricula,
            'numero_modulo' => $numeroModulo,
            'tipo' => 'pago-modulo'
        ];

        Log::error("Filters getPagoModulo PagoController: " . implode(", ", $filters));

        $result = $this->pagoService->getPagoModuloPDF($filters);

        if ($result['status'] === 'error') {
            return response($result['message'], 404);
        }

        // Devolver el archivo PDF como respuesta binaria
        $filename = "pago_modulo_{$idMatricula}_{$numeroModulo}.pdf";

        Log::error("Filename getPagoModulo PagoController: " . $filename);

        return response($result['pdfContent'], 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "inline; filename=\"{$filename}\"");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();

            $pagoCreateDTO = PagoCreateDTO::from($data);

            $pago = $this->pagoService->createPago($pagoCreateDTO);

            return response()->json([
                'result' => true,
                'data' => $pago,
                'message' => 'Pago registrado correctamente'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'result' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error("Error creating pago: " . $e->getMessage());
            
            return response()->json([
                'result' => false,
                'message' => 'Error al crear pago: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $pago = $this->pagoService->getPagoById($id);

            if (!$pago) {
                return response()->json([
                    'result' => false,
                    'message' => 'Pago no encontrado',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'result' => true,
                'data' => $pago,
                'message' => 'Pago encontrado correctamente'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching pago (id: {$id}): " . $e->getMessage());
            
            return response()->json([
                'result' => false,
                'message' => 'Error al obtener el pago: ' . $e->getMessage()
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
