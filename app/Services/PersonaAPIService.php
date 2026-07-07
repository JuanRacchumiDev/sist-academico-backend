<?php

namespace App\Services;

use App\Repositories\Contracts\IPersonaRepository;
use App\DTOs\Persona\PersonaAPIDTO;
use App\Models\Persona;
use App\Repositories\Contracts\IDetalleParametroRepository;
use App\Services\Contracts\IPersonaAPIService;
use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class PersonaAPIService implements IPersonaAPIService
{
    protected IPersonaRepository $personaRepository;
    protected IDetalleParametroRepository $detalleRepository;

    public function __construct(
        IPersonaRepository $personaRepository,
        IDetalleParametroRepository $detalleRepository
    ) {
        $this->personaRepository = $personaRepository;
        $this->detalleRepository = $detalleRepository;
    }

    public function query(string $tipoDocumento, string $numeroDocumento): array
    {
        if ($tipoDocumento !== 'DNI') {
            throw new Exception("La API de Factiliza solo soporta consultas DNI.");
        }

        $response = $this->callAPI($numeroDocumento);

        return $response;
    }

    public function queryAndRegister(
        string $tipoDocumento,
        string $numeroDocumento,
        string $nombreGrupo,
        string $userCrea
    ): Persona {
        if ($tipoDocumento !== 'DNI') {
            throw new Exception("La API de Factiliza solo soporta consultas DNI.");
        }

        $response = $this->callAPI($numeroDocumento);
        $response['user_crea'] = $userCrea;

        Log::debug('PersonaAPIService: Respuesta de callAPI recibida.', ['api_response_data' => $response]);

        // Crear DTO a partir de la respuesta
        try {
            // Aquí usamos el DTO para mapear y validar los datos de la respuesta
            $dto = PersonaAPIDTO::fromAPIResponse($response);

            Log::info('PersonaAPIService: DTO creado con éxito.', ['dto_data' => (array) $dto]);

            // Establecer el valor de 'nombre_grupo' en el DTO
            if ($nombreGrupo) {
                $dto = $dto->withNombreGrupo($nombreGrupo); // <-- USAR UN NUEVO MÉTODO DEL DTO
            }
        } catch (Exception $e) {
            Log::error('PersonaAPIService: Error al procesar DTO desde la API.', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Manejo de errores de mapeo/validación del DTO
            throw new Exception("Error al procesar la respuesta de la API externa: " . $e->getMessage());
        }

        $persona = $this->personaRepository->updateOrCreateFromAPI($dto);

        Log::info('Obteniendo data persona', ['persona' => $persona]);

        // Obtener el nombre del grupo
        $nombreGrupo = $dto->nombre_grupo;

        Log::info('Obteniendo data nombreGrupo', ['nombreGrupo' => $nombreGrupo]);

        // Obtener el usuario creador
        $userCrea = $dto->user_crea ?? 'systemapi';

        Log::info('Obteniendo data userCrea', ['userCrea' => $userCrea]);

        // Obteniendo grupo
        $grupo = $this->detalleRepository->findByNombreUrl($nombreGrupo);

        Log::info('Obteniendo detalle de grupo', ['grupo' => $grupo]);

        // Adjuntar el grupo (si existe el código)
        if ($grupo) {
            $codigoGrupo = $grupo->codigo;

            $persona->grupos()->attach($codigoGrupo, [
                'user_crea' => $userCrea
            ]);
        }

        return $persona;
    }

    public function callAPI(string $numeroDocumento): array
    {
        $apiBaseUrl = config('services.factiliza.url');

        $token = config('services.factiliza.token');

        if (!$token) {
            throw new Exception("FACTILIZA_API_TOKEN no está configurado en el archivo .env.");
        }

        $url = "{$apiBaseUrl}/dni/info/{$numeroDocumento}";

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$token}",
            ])->get($url);

            if ($response->failed()) {
                // Si el HTTP falla (4xx, 5xx), lanza una excepción
                throw new Exception("La API externa devolvió un error HTTP: " . $response->status());
            }

            $responseData = $response->json();

            if (!isset($responseData['success']) || $responseData['success'] !== true) {
                $message = $responseData['message'] ?? 'Error desconocido al consultar DNI.';
                throw new Exception("La API de Factiliza devolvió un error: {$message}");
            }

            return $responseData['data'];
        } catch (\Throwable $e) {
            throw new Exception("Fallo en la conexión o la API: " . $e->getMessage());
        }
    }
}
