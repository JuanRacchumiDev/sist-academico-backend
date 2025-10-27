<?php

namespace App\Providers;

use App\Repositories\Contracts\IDetalleParametroRepository;
use App\Repositories\Contracts\IEventoRepository;
use App\Repositories\Contracts\IParametroRepository;
use App\Repositories\Contracts\IPersonaRepository;
use App\Repositories\Contracts\IUserRepository;
// use App\Repositories\Contracts\IPlantillaRepository;
// use App\Repositories\Contracts\ICertificadoRepository;

use App\Repositories\Eloquent\DetalleParametroRepository;
use App\Repositories\Eloquent\EventoRepository;
use App\Repositories\Eloquent\ParametroRepository;
use App\Repositories\Eloquent\PersonaRepository;
use App\Repositories\Eloquent\UserRepository;

use App\Services\Contracts\IDetalleParametroService;
use App\Services\Contracts\IParametroService;
use App\Services\Contracts\IPersonaService;
use App\Services\Contracts\IUserService;
use App\Services\Contracts\IEventoService;

use App\Services\DetalleParametroService;
use App\Services\EventoService;
use App\Services\ParametroService;
use App\Services\PersonaService;
use App\Services\UserService;

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
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
