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
use App\Http\Controllers\Api\CertificadoController;
use App\Http\Controllers\Api\AdjuntoController;
use App\Http\Controllers\Api\InstitucionController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('test', function () {
        return response()->json([
            'success' => true,
            'message' => 'Acceso correcto al enrutador de la API'
        ]);
    });

    Route::prefix('personas')->group(function () {
        Route::get('consultar-documento/{tipoDocumento}/{numeroDocumento}', [PersonaController::class, 'consultarDocumento'])->name('personas.consultarDocumento');
    });

    Route::get('/generate-ficha-pdf', [PdfController::class, 'fichaTest'])->name('generate.pdf.test');

    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'validateUnique'])->name('login');
    });

    Route::middleware('auth:api')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('logout', [AuthController::class, 'logout'])->name('logout');
            Route::post('refresh', [AuthController::class, 'refresh'])->name('refresh');
            Route::get('authenticated', [AuthController::class, 'authenticated'])->name('authenticated');
        });

        Route::apiResource('parametros', ParametroController::class);

        Route::prefix('catalogos')->group(function () {
            Route::get('/', [DetalleParametroController::class, 'getFiltered'])->name('catalogos.all');
            Route::get('/{clase}/{nParDetCodigo}', [DetalleParametroController::class, 'show'])->name('catalogos.show');
            Route::get('/{clase}', [DetalleParametroController::class, 'getFilteredPaginate'])->name('catalogos.paginate');
            Route::post('/{clase}', [DetalleParametroController::class, 'store'])->name('catalogos.store');
            Route::patch('/{clase}/{nParDetCodigo}', [DetalleParametroController::class, 'update'])->name('catalogos.update');
        });

        Route::prefix('personas')->group(function () {
            Route::get('/', [PersonaController::class, 'index'])->name('personas.index');
            Route::get('/grupo/{grupo}/paginate', [PersonaController::class, 'getFilteredPaginate'])->name('personas.paginate');
            Route::get('/grupo/{grupo}', [PersonaController::class, 'getFiltered'])->name('personas');
            Route::get('/{id}', [PersonaController::class, 'show'])->name('personas.show');
            Route::post('/store-api', [PersonaController::class, 'storeApi'])->name('personas.storeApi');
            Route::post('/', [PersonaController::class, 'store'])->name('personas.store');
            Route::patch('/{id}', [PersonaController::class, 'update'])->name('personas.update');
            Route::delete('/{id}', [PersonaController::class, 'destroy'])->name('personas.destroy');
        });

        Route::prefix('plantillas')->group(function () {
            Route::post('/', [PlantillaController::class, 'store'])->name('plantillas.store');
        });

        Route::prefix('programas')->group(function () {
            Route::get('/paginate', [ProgramaController::class, 'getFilteredPaginate'])->name('programas.paginate');
            Route::get('/{programa}/descargar-plan', [ProgramaController::class, 'downloadPlan'])->name('programas.descargarplan');
            Route::get('/{id}', [ProgramaController::class, 'show'])->name('programas.show');
            Route::get('/', [ProgramaController::class, 'index'])->name('programas');
            Route::post('/', [ProgramaController::class, 'store'])->name('programas.store');
            Route::patch('/{id}', [ProgramaController::class, 'update'])->name('programas.update');
        });

        Route::prefix('modulos')->group(function () {
            Route::get('/paginate', [ModuloController::class, 'getFilteredPaginate'])->name('modulos.paginate');
            Route::get('/{id}', [ModuloController::class, 'show'])->name('modulos.show');
            Route::get('/', [ModuloController::class, 'index'])->name('modulos');
            Route::post('/', [ModuloController::class, 'store'])->name('modulos.store');
        });

        Route::prefix('matriculas')->group(function () {
            Route::get('{id}/modulos-por-pagar', [MatriculaController::class, 'getModulosPorPagar'])->name('matriculas.modulosporpagar');
            Route::get('{id}/modulos-pagados', [MatriculaController::class, 'getModulosPagados'])->name('matriculas.modulospagados');
            Route::get('/certificado', [MatriculaController::class, 'downloadCertificado'])->name('matriculas.certificado');
            Route::get('/cronograma-pagos', [MatriculaController::class, 'downloadCronograma'])->name('matriculas.cronogramapagos');
            Route::get('/paginate', [MatriculaController::class, 'getFilteredPaginate'])->name('matriculas.paginate');
            Route::get('/{id}', [MatriculaController::class, 'show'])->name('matriculas.show');
            Route::get('/{id}/pdf', [MatriculaController::class, 'downloadFicha'])->name('matriculas.pdf');
            Route::get('/', [MatriculaController::class, 'index'])->name('matriculas');
            Route::post('/', [MatriculaController::class, 'store'])->name('matriculas.store');
            Route::patch('/{id}', [MatriculaController::class, 'update'])->name('matriculas.update');
            Route::delete('/{id}/pdf', [MatriculaController::class, 'regenerateFicha'])->name('matriculas.delete');
        });

        Route::prefix('instituciones')->group(function () {
            Route::get('/', [InstitucionController::class, 'index'])->name('instituciones');
        });

        Route::prefix('pagos')->group(function () {
            Route::get('/paginate', [PagoController::class, 'getFilteredPaginate'])->name('pagos.paginate');
            Route::get('/matricula', [PagoController::class, 'getMatricula'])->name('pagos.matricula');
            Route::get('/modulo', [PagoController::class, 'getPagoModulo'])->name('pagos.modulo');
            Route::get('{id}', [PagoController::class, 'show'])->name('pagos.show');
            Route::get('/', [PagoController::class, 'index'])->name('pagos');
            Route::post('/', [PagoController::class, 'store'])->name('pagos.store');
        });

        Route::prefix('certificados')->group(function () {
            Route::get('/', [CertificadoController::class, 'index'])->name('certificados');
            Route::post('/', [CertificadoController::class, 'store'])->name('certificados.store');
            Route::get('/{id}', [CertificadoController::class, 'show'])->name('certificados.show');
            Route::patch('/{id}', [CertificadoController::class, 'update'])->name('certificados.update');
            Route::delete('/{id}', [CertificadoController::class, 'destroy']);
        });

        Route::prefix('adjuntos')->group(function () {
            Route::get('/paginate', [AdjuntoController::class, 'getFilteredPaginate'])->name('adjuntos.paginate');
            Route::get('/', [AdjuntoController::class, 'index'])->name('adjuntos');
            Route::post('/verificar', [AdjuntoController::class, 'verificarExistencia'])->name('adjuntos.verificar');
            Route::get('/{id}', [AdjuntoController::class, 'show'])->name('adjuntos.show');
            Route::post('/', [AdjuntoController::class, 'store'])->name('adjuntos.store');
            Route::patch('/{id}', [AdjuntoController::class, 'update'])->name('adjuntos.update');
        });

        Route::prefix('usuarios')->group(function () {
            Route::get('/paginate', [UserController::class, 'getFilteredPaginate'])->name('users.paginate');
            Route::get('/', [UserController::class, 'index'])->name('users.index');
            Route::get('/{id}', [UserController::class, 'show'])->name('users.show');
            Route::get('/validate', [UserController::class, 'validate'])->name('users.validate');
            Route::post('/', [UserController::class, 'store'])->name('users.store');
        });
    });
});
