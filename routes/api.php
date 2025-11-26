<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DetalleParametroController;
use App\Http\Controllers\Api\EventoController;
use App\Http\Controllers\Api\MatriculaController;
use App\Http\Controllers\Api\ModuloController;
use App\Http\Controllers\Api\PagoController;
use App\Http\Controllers\Api\ParametroController;
use App\Http\Controllers\Api\PdfController;
use App\Http\Controllers\Api\PersonaController;
use App\Http\Controllers\Api\PlantillaController;
use App\Http\Controllers\Api\ProgramaController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function() {

    Route::get('status', function() {
        return response()->json([
            'status' => 'success',
            'message' => 'API V1 está en línea y accesible'
        ], 200);
    });

    Route::get('/generate-ficha-pdf', [PdfController::class, 'fichaTest']);

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
            // Route::get('/{clase}', [DetalleParametroController::class, 'index']);
            Route::get('/{clase}', [DetalleParametroController::class, 'getFilteredPaginate']);
            Route::post('/{clase}', [DetalleParametroController::class, 'store']);
            Route::patch('/{clase}/{nParDetCodigo}', [DetalleParametroController::class, 'update']);
        });

        Route::apiResource('eventos', EventoController::class);

        Route::prefix('personas')->group(function() {
            Route::get('grupo/{nombreGrupo}/paginate', [PersonaController::class, 'getFilteredPaginate']);
            Route::get('grupo/{nombreGrupo}', [PersonaController::class, 'getAll']);
            Route::get('/{id}', [PersonaController::class, 'show']);
            Route::post('/', [PersonaController::class, 'store']);
        });

        Route::prefix('plantillas')->group(function () {
            Route::post('/', [PlantillaController::class, 'store']);
        });

        Route::prefix('programas')->group(function() {
            Route::get('/paginate', [ProgramaController::class, 'getFilteredPaginate']);
            Route::get('/{programa}/descargar-plan', [ProgramaController::class, 'downloadPlan']);
            Route::get('/{id}', [ProgramaController::class, 'show']);
            Route::get('/', [ProgramaController::class, 'index']);
            Route::post('/', [ProgramaController::class, 'store']);
        });

        Route::prefix('modulos')->group(function() {
            Route::get('/paginate', [ModuloController::class, 'getFilteredPaginate']);
            Route::get('/{id}', [ModuloController::class, 'show']);
            Route::get('/', [ModuloController::class, 'index']);
            Route::post('/', [ModuloController::class, 'store']);
        });

        Route::prefix('matriculas')->group(function() {
            Route::get('/paginate', [MatriculaController::class, 'getFilteredPaginate']);
            Route::get('/ficha', [MatriculaController::class, 'downloadFichaPdf']);
            Route::get('{id}', [MatriculaController::class, 'show']);
            Route::get('/', [MatriculaController::class, 'index']);
            Route::post('/', [MatriculaController::class, 'store']);
        });

        Route::prefix('pagos')->group(function() {
            Route::get('{id}', [PagoController::class, 'show']);
            Route::get('/', [PagoController::class, 'index']);
            Route::post('/', [PagoController::class, 'store']);
        });
    });
});