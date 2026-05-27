<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetalleParametro;
use App\Models\User;
use App\Services\Contracts\IUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    protected IUserService $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    public function validateUnique(Request $request): JsonResponse
    {
        $credenciales = $request->only(['email', 'password']);
        
        $email = $credenciales['email'];
        
        $filters = ['email' => $email];

        Log::info('Validate filters', $filters);

        try {
            $usuario = $this->userService->getUserByParams($filters);
        
            Log::info('Validate usuario', $usuario ? $usuario->toArray() : []);

            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'data' => [],
                    'message' => 'Usuario no encontrado. Verifique sus credenciales'
                ], 401);
            }

            if (!$token = JWTAuth::attempt($credenciales)) {
                return response()->json([
                    'result' => false,
                    'error' => 'Contraseña incorrecta. Verifique sus credenciales'
                ], 401);
            }
            
            return $this->respondWithToken($token, $usuario);
            
            // return response()->json([
            //     'success' => true,
            //     'data' => $user,
            //     'message' => 'Usuario encontrado correctamente'
            // ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching usuario: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el usuario: ' . $e->getMessage()
            ], 500);
        } catch (JWTException $e) {
            Log::error("Error al crear token: " . $e->getMessage());
            return response()->json([
                'result' => false,
                'error' => 'No se puede crear el token de acceso'
            ], 500);
        }
    }

    // public function login(Request $request): JsonResponse
    // {
    //     Log::info('Login Request Data', $request->all());
    //     $credenciales = $request->only('email', 'password');

    //     try {
    //         $usuario = $this->userService->getUserByEmail($request->email);

    //         if (!$usuario) {
    //             return response()->json([
    //                 'result' => false, 
    //                 'error' => 'Usuario no encontrado. Verifique sus credenciales'
    //             ], 401);
    //         }

    //         if (!$token = JWTAuth::attempt($credenciales)) {
    //             return response()->json([
    //                 'result' => false,
    //                 'error' => 'Contraseña incorrecta. Verifique sus credenciales'
    //             ], 401);
    //         }

    //         return $this->respondWithToken($token, $usuario);
    //     } catch (JWTException $e) {
    //         return response()->json([
    //             'result' => false,
    //             'error' => 'No se puede crear el token'
    //         ], 500);
    //     }
    // }

    public function logout(): JsonResponse
    {
        try {
            Auth::logout();

            return response()->json([
                'result' => true,
                'message' => 'Sesión cerrada exitosamente',
                'status' => 200
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'result' => false,
                'error' => 'Error al cerrar sesión, por favor trata nuevamente: ' . $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    public function refresh(): JsonResponse {
        try {
            return $this->respondWithToken(Auth::refresh(), null);
        } catch (JWTException $e) {
            return response()->json([
                'result' => false,
                'error' => 'Token no válido o expirado'
            ], 500);
        }
    }

    public function authenticated(): JsonResponse
    {
        try {
            // Obtener el usuario autenticado
            $user = Auth::user();
      
            if (!$user) {
                return response()->json([
                    'result' => false,
                    'error' => 'Token expirado o no válido'
                ], 401);
            }

            // Obtener el perfil
            $perfil = DetalleParametro::find($user->id_perfil);

            return response()->json([
                'result' => true,
                'user' => $user,
                'perfil_nombre' => $perfil->nombre,
                'message' => 'Usuario autenticado'
            ], 201);
        } catch (JWTException $e) {
            return response()->json([
                'result' => false,
                'error' => 'No autorizado. Token no válido o expirado'
            ], 401);
        }
    }

    protected function respondWithToken(string $token, ?User $usuario): JsonResponse
    {
        $nombrePerfil = "";
        $nombrePersona = "";

        if ($usuario && $usuario->perfil) {
            $perfil = $usuario->perfil;
            $nombrePerfil = $perfil->nombre_url;
        }

        if ($usuario && $usuario->persona) {
            $persona = $usuario->persona;
            $nombrePersona = $persona->nombre_completo; 
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'result' => true,
            'message' => 'Usuario autenticado correctamente',
            'usuario' => [
                "id" => $usuario->id ?? null,
                "name" => $usuario->name ?? null,
                "email" => $usuario->email ?? null,
                "nombre_perfil" => $nombrePerfil,
                "nombre_completo" => $nombrePersona
            ]
        ], 200);
    }
}
