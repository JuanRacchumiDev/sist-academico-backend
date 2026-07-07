<?php

namespace App\Http\Controllers\Api;

use App\DTOs\User\UserCreateDTO;
use App\DTOs\User\UserUpdateDTO;
use App\Http\Controllers\Controller;
use App\Services\Contracts\IDetalleParametroService;
use App\Services\Contracts\IPersonaService;
use App\Services\Contracts\IUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function __construct(
        protected IUserService $userService,
        protected IDetalleParametroService $detalleParametroService,
        protected IPersonaService $personaService
    ) {
        $this->userService = $userService;
        $this->detalleParametroService = $detalleParametroService;
        $this->personaService = $personaService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $users = $this->userService->getAllUsers();

            if ($users->isEmpty()) {
                return response()->json([
                    'result' => true,
                    'data' => [],
                    'message' => 'No se encontraron usuarios'
                ], 200);
            }

            return response()->json([
                'result' => true,
                'data' => $users,
                'message' => 'Listado de usuarios correctos'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching usuarios: ", $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuarios: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getFilteredPaginate(Request $request): JsonResponse
    {
        try {
            $filters = $request->only([
                'name',
                'email',
                'id_perfil'
            ]);

            $perPage = $request->input('per_page', 10);

            $users = $this->userService->getAllUsersWithFilters($filters, $perPage);

            if ($users->isEmpty()) {
                return response()->json([
                    'result' => false,
                    'data' => [],
                    'message' => 'No se encontraron resultados'
                ], 200);
            }

            return response()->json([
                'result' => true,
                'data' => $users,
                'message' => 'Resultados encontrados correctamente',
                'pagination' => [
                    'total' => $users->total(),
                    'per_page' => $users->perPage(),
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'from' => $users->firstItem(),
                    'to' => $users->lastItem()
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching usuarios: " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al obtener usuarios',
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

            $userCreateDTO = UserCreateDTO::validateAndCreate($data);

            $usuario = $this->userService->createUser($userCreateDTO);

            return response()->json([
                'result' => true,
                'data' => $usuario,
                'message' => 'Usuario registrado correctamente',
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
            Log::error("Error creating usuario: " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al crear usuario : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $user = $this->userService->getUserById($id);

            if (!$user) {
                return response()->json([
                    'result' => false,
                    'message' => 'Usuario no encontrado',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'result' => true,
                'data' => $user,
                'message' => 'Usuario encontrado correctamente'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching usuario (id: {$id}): " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el usuario: ' . $e->getMessage()
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

            $userUpdateDTO = UserUpdateDTO::from([
                ...$data,
                'id' => $id
            ]);

            $user = $this->userService->updateUser($id, $userUpdateDTO);

            if (!$user) {
                return response()->json([
                    'result' => false,
                    'data' => [],
                    'message' => 'Error al actualizar al usuario'
                ], 422);
            }

            return response()->json([
                'result' => true,
                'data' => $user,
                'message' => 'Usuario actualizado correctamente'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'result' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error("Error updating usuario (ID: {$id}): " . $e->getMessage());

            return response()->json([
                'result' => false,
                'message' => 'Error al actualizar el usuario'
            ], 500);
        }
    }
}
