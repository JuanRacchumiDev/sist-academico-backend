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

        $this->app->bind(
            IInstitucionRepository::class,
            InstitucionRepository::class
        );

        $this->app->bind(
            ICertificadoRepository::class,
            CertificadoRepository::class
        );

        $this->app->bind(
            IAdjuntoRepository::class,
            AdjuntoRepository::class
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

        $this->app->bind(
            ICertificadoService::class,
            CertificadoService::class
        );

        $this->app->bind(
            IAdjuntoService::class,
            AdjuntoService::class
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
