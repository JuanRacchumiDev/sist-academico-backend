<?php

namespace App\Providers;

use App\Repositories\Contracts\IDetalleParametroRepository;
use App\Repositories\Contracts\IEventoRepository;
use App\Repositories\Contracts\IParametroRepository;
use App\Repositories\Contracts\IPersonaRepository;
use App\Repositories\Contracts\IUserRepository;
use App\Repositories\Contracts\IPlantillaRepository;
use App\Repositories\Contracts\IProgramaRepository;
use App\Repositories\Contracts\IMatriculaRepository;
use App\Repositories\Contracts\IPagoRepository;
use App\Repositories\Contracts\IModuloRepository;
use App\Repositories\Contracts\IInstitucionRepository;
use App\Repositories\Contracts\ICertificadoRepository;
use App\Repositories\Contracts\IAdjuntoRepository;
use App\Repositories\Contracts\ICuestionarioRepository;
use App\Repositories\Contracts\IPreguntaRepository;

use App\Repositories\Eloquent\DetalleParametroRepository;
use App\Repositories\Eloquent\EventoRepository;
use App\Repositories\Eloquent\ParametroRepository;
use App\Repositories\Eloquent\PersonaRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\PlantillaRepository;
use App\Repositories\Eloquent\ProgramaRepository;
use App\Repositories\Eloquent\MatriculaRepository;
use App\Repositories\Eloquent\PagoRepository;
use App\Repositories\Eloquent\ModuloRepository;
use App\Repositories\Eloquent\InstitucionRepository;
use App\Repositories\Eloquent\CertificadoRepository;
use App\Repositories\Eloquent\AdjuntoRepository;
use App\Repositories\Eloquent\CuestionarioRepository;
use App\Repositories\Eloquent\PreguntaRepository;

use App\Services\Contracts\IDetalleParametroService;
use App\Services\Contracts\IParametroService;
use App\Services\Contracts\IPersonaService;
use App\Services\Contracts\IUserService;
use App\Services\Contracts\IEventoService;
use App\Services\Contracts\IPlantillaService;
use App\Services\Contracts\IProgramaService;
use App\Services\Contracts\IMatriculaService;
use App\Services\Contracts\IPagoService;
use App\Services\Contracts\IModuloService;
use App\Services\Contracts\IPersonaAPIService;
use App\Services\Contracts\ICertificadoService;
use App\Services\Contracts\IAdjuntoService;
use App\Services\Contracts\ICuestionarioService;
use App\Services\Contracts\IInstitucionService;
use App\Services\Contracts\IStorageService;

use App\Services\DetalleParametroService;
use App\Services\EventoService;
use App\Services\ParametroService;
use App\Services\PersonaService;
use App\Services\UserService;
use App\Services\PlantillaService;
use App\Services\ProgramaService;
use App\Services\MatriculaService;
use App\Services\PagoService;
use App\Services\ModuloService;
use App\Services\PersonaAPIService;
use App\Services\CertificadoService;
use App\Services\AdjuntoService;
use App\Services\CuestionarioService;
use App\Services\InstitucionService;
use App\Services\StorageService;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Enlazando el repositorio de parámetro
        $this->app->bind(
            IParametroRepository::class,
            ParametroRepository::class
        );

        // Enlazando el repositorio de detalle parámetro
        $this->app->bind(
            IDetalleParametroRepository::class,
            DetalleParametroRepository::class
        );

        // Enlazando el repositorio de persona
        $this->app->bind(
            IPersonaRepository::class,
            PersonaRepository::class
        );

        // Enlazando el repositorio de usuario
        $this->app->bind(
            IUserRepository::class,
            UserRepository::class
        );

        // Enlazando el repositorio de evento
        $this->app->bind(
            IEventoRepository::class,
            EventoRepository::class
        );

        // Enlazando el repositorio de plantilla
        $this->app->bind(
            IPlantillaRepository::class,
            PlantillaRepository::class
        );

        // Enlazando el repositorio de programa
        $this->app->bind(
            IProgramaRepository::class,
            ProgramaRepository::class
        );

        // Enlazando el repositorio de matrícula
        $this->app->bind(
            IMatriculaRepository::class,
            MatriculaRepository::class
        );

        // Enlazando el repositorio de pago
        $this->app->bind(
            IPagoRepository::class,
            PagoRepository::class
        );

        // Enlazando el repositorio de módulo
        $this->app->bind(
            IModuloRepository::class,
            ModuloRepository::class
        );

        // Enlazando el repositorio de institucion
        $this->app->bind(
            IInstitucionRepository::class,
            InstitucionRepository::class
        );

        // Enlazando el repositorio de certificado
        $this->app->bind(
            ICertificadoRepository::class,
            CertificadoRepository::class
        );

        // Enlazando el repositorio de adjunto
        $this->app->bind(
            IAdjuntoRepository::class,
            AdjuntoRepository::class
        );

        // Enlazando el repositorio de pregunta
        $this->app->bind(
            IPreguntaRepository::class,
            PreguntaRepository::class
        );

        // Enlazando el repositorio de cuestionario
        $this->app->bind(
            ICuestionarioRepository::class,
            CuestionarioRepository::class
        );

        /* --------- servicios ----------- */
        // Enlazando el servicio de parámetro
        $this->app->bind(
            IParametroService::class,
            ParametroService::class
        );

        // Enlazando el servicio de detalle parámetro
        $this->app->bind(
            IDetalleParametroService::class,
            DetalleParametroService::class
        );

        // Enlazando el servicio de persona
        $this->app->bind(
            IPersonaService::class,
            PersonaService::class
        );

        // Enlazando el servicio de usuario
        $this->app->bind(
            IUserService::class,
            UserService::class
        );

        // Enlazando el servicio de evento
        $this->app->bind(
            IEventoService::class,
            EventoService::class
        );

        // Enlazando el servicio de plantilla
        $this->app->bind(
            IPlantillaService::class,
            PlantillaService::class
        );

        // Enlazando el servicio de programa
        $this->app->bind(
            IProgramaService::class,
            ProgramaService::class
        );

        // Enlazando el servicio de matrícula
        $this->app->bind(
            IMatriculaService::class,
            MatriculaService::class
        );

        // Enlazando el servicio de pago
        $this->app->bind(
            IPagoService::class,
            PagoService::class
        );

        // Enlazando el servicio de módulo
        $this->app->bind(
            IModuloService::class,
            ModuloService::class
        );

        // Enlazando el servicio de personaApi
        $this->app->bind(
            IPersonaAPIService::class,
            PersonaAPIService::class
        );

        // Enlazando el servicio de certificado
        $this->app->bind(
            ICertificadoService::class,
            CertificadoService::class
        );

        // Enlazando el servicio de adjunto
        $this->app->bind(
            IAdjuntoService::class,
            AdjuntoService::class
        );

        // Enlazando el servicio de cuestionario
        $this->app->bind(
            ICuestionarioService::class,
            CuestionarioService::class
        );

        // Enlazando el servicio de institución
        $this->app->bind(
            IInstitucionService::class,
            InstitucionService::class
        );

        // Enlazando el servicio de storage
        $this->app->bind(
            IStorageService::class,
            StorageService::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
