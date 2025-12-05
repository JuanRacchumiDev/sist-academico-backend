<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DetalleParametroController;
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
    Route::get('test', function() {
        return response()->json([
            'success' => true,
            'message' => 'Acceso correcto al enrutador de la API'
        ]);
    });

    Route::prefix('personas')->group(function() {
        Route::get('consultar-documento/{tipoDocumento}/{numeroDocumento}', [PersonaController::class, 'consultarDocumento'])->name('personas.consultarDocumento');
    });

    Route::get('/generate-ficha-pdf', [PdfController::class, 'fichaTest'])->name('generate.pdf.test');

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
            Route::get('/', [DetalleParametroController::class, 'getFiltered'])->name('catalogos.all');
            Route::get('/{clase}/{nParDetCodigo}', [DetalleParametroController::class, 'show'])->name('catalogos.show');
            Route::get('/{clase}', [DetalleParametroController::class, 'getFilteredPaginate'])->name('catalogos.paginate');
            Route::post('/{clase}', [DetalleParametroController::class, 'store'])->name('catalogos.store');
            Route::patch('/{clase}/{nParDetCodigo}', [DetalleParametroController::class, 'update'])->name('catalogos.update');
        });

        Route::prefix('personas')->group(function() {
            Route::get('grupo/{nombreGrupo}/paginate', [PersonaController::class, 'getFilteredPaginate'])->name('personas.paginate');
            Route::get('grupo/{nombreGrupo}', [PersonaController::class, 'getAll'])->name('personas');
            Route::get('/{id}', [PersonaController::class, 'show'])->name('personas.show');
            Route::post('store-api', [PersonaController::class, 'storeApi'])->name('personas.storeApi');
            Route::post('/', [PersonaController::class, 'store'])->name('personas.store');
            Route::patch('/{id}', [PersonaController::class, 'update'])->name('personas.update');
        });

        Route::prefix('plantillas')->group(function () {
            Route::post('/', [PlantillaController::class, 'store'])->name('plantillas.store');
        });

        Route::prefix('programas')->group(function() {
            Route::get('/paginate', [ProgramaController::class, 'getFilteredPaginate'])->name('programas.paginate');
            Route::get('/{programa}/descargar-plan', [ProgramaController::class, 'downloadPlan'])->name('programas.descargarplan');
            Route::get('/{id}', [ProgramaController::class, 'show'])->name('programas.show');
            Route::get('/', [ProgramaController::class, 'index'])->name('programas');
            Route::post('/', [ProgramaController::class, 'store'])->name('programas.store');
        });

        Route::prefix('modulos')->group(function() {
            Route::get('/paginate', [ModuloController::class, 'getFilteredPaginate'])->name('modulos.paginate');
            Route::get('/{id}', [ModuloController::class, 'show'])->name('modulos.show');
            Route::get('/', [ModuloController::class, 'index'])->name('modulos');
            Route::post('/', [ModuloController::class, 'store'])->name('modulos.store');
        });

        Route::prefix('matriculas')->group(function() {
            Route::get('/paginate', [MatriculaController::class, 'getFilteredPaginate'])->name('matriculas.paginate');
            Route::get('/ficha', [MatriculaController::class, 'getFicha'])->name('matriculas.ficha');
            Route::get('{id}', [MatriculaController::class, 'show'])->name('matriculas.show');
            Route::get('/', [MatriculaController::class, 'index'])->name('matriculas');
            Route::post('/', [MatriculaController::class, 'store'])->name('matriculas.store');
        });

        Route::prefix('pagos')->group(function() {
            Route::get('{id}', [PagoController::class, 'show'])->name('pagos.show');
            Route::get('/', [PagoController::class, 'index'])->name('pagos');
            Route::post('/', [PagoController::class, 'store'])->name('pagos.store');
        });
    });
});