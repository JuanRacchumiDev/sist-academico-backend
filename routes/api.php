<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DetalleParametroController;
use App\Http\Controllers\Api\EventoController;
use App\Http\Controllers\Api\ParametroController;
use App\Http\Controllers\Api\PersonaController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function() {

    Route::get('status', function() {
        return response()->json([
            'status' => 'success',
            'message' => 'API V1 está en línea y accesible'
        ], 200);
    });

    Route::prefix('auth')->group(function() {
        Route::post('login', [AuthController::class, 'login'])->name('login');
    });

    Route::middleware('auth:api')->group(function() {
        Route::prefix('auth')->group(function() {
            Route::post('logout', [AuthController::class, 'logout'])->name('logout');
            Route::post('refresh', [AuthController::class, 'refresh'])->name('refresh');
            Route::get('authenticated', [AuthController::class, 'authenticated'])->name('authenticated');
        });

        Route::apiResource('parametros', ParametroController::class);

        Route::prefix('catalogos')->group(function() {
            Route::get('/', [DetalleParametroController::class, 'getFiltered']);
            Route::get('/{clase}/{nParDetCodigo}', [DetalleParametroController::class, 'show']);
            Route::get('/{clase}', [DetalleParametroController::class, 'index']);
            Route::post('/{clase}', [DetalleParametroController::class, 'store']);
            Route::patch('/{clase}/{nParDetCodigo}', [DetalleParametroController::class, 'update']);
        });

        Route::apiResource('eventos', EventoController::class);

        Route::prefix('personas')->group(function() {
            Route::post('/', [PersonaController::class, 'store']);
            Route::get('grupo/{nombreGrupo}', [PersonaController::class, 'indexByGrupo']);
        });
    });
});