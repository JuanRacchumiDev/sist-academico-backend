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
    )
    {
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
                    'success' => true,
                    'data' => [],
                    'message' => 'Usuarios no registrados'
                ], 200);
            }

            return response()->json([
                'success' => true,
                'data' => $users,
                'message' => 'Usuarios listados correctamente'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching usuarios: ", $e->getMessage());
        
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuarios: '. $e->getMessage()
            ], 500);
        }
    }

    public function getAllWithFilters(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['name', 'email', 'nPerfilId', 'bEstado']);
            $perPage = $request->input('per_page', 10);

            $users = $this->userService->getAllUsersWithFilters($filters, $perPage);

            if ($users->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'Usuarios no registrados'
                ]);
            }
            
            return response()->json([
                'success' => true,
                'data' => $users,
                'pagination' => [
                    'total' => $users->total(),
                    'per_page' => $users->perPage(),
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'from' => $users->firstItem(),
                    'to' => $users->lastItem()
                ],
                'message' => 'Usuarios listados correctamente'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching usuarios: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuarios: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $userCreateDTO = UserCreateDTO::from($request->all());

            // $perfil = $this->detalleParametroService->getByCodigo($userCreateDTO->id_perfil);
            $usuario = $this->userService->createUser($userCreateDTO);

            return response()->json([
                'success' => true,
                'data' => $usuario,
                'message' => 'Usuario registrado correctamente'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error("Error creating usuario: " . $e->getMessage());

            return response()->json([
                'success' => false,
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
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Usuario encontrado correctamente'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching usuario (ID: {$id}): " . $e->getMessage());

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
            $userUpdateDTO = UserUpdateDTO::from([
                'id' => $id,
                ...$request->all()
            ]);

            $user = $this->userService->updateUser($userUpdateDTO);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar al usuario'
                ], 422);
            }
            
            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Usuario actualizado correctamente'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error("Error updating usuario (ID: {$id}): " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el usuario'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): Response|JsonResponse
    {
        try {
            if (!$this->userService->deleteUser($id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error al eliminar al usuario (ID: {$id}): " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el usuario: ' . $e->getMessage()
            ], 500);
        }
    }
}
