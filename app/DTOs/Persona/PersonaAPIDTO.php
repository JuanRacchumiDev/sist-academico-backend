<?php

namespace App\DTOs\Persona;

use Carbon\Carbon;

class PersonaAPIDTO
{
    public readonly string $nombre_completo;

    public function __construct(
        public readonly int $id_tipodocumento,
        public readonly string $numero_documento,
        public readonly string $nombres,
        public readonly string $apellido_paterno,
        public readonly string $apellido_materno,
        public readonly string $sexo,
        public readonly ?string $nombre_grupo = null,
        public readonly ?string $departamento = null,
        public readonly ?string $provincia = null,
        public readonly ?string $distrito = null,
        public readonly ?string $direccion = null,
        public readonly ?string $direccion_completa = null,
        public readonly ?string $ubigeo_reniec = null,
        public readonly ?string $ubigeo = null,
        public readonly ?string $fecha_nacimiento = null,
        public readonly ?string $estado_civil = null,
        public readonly ?string $user_crea = null,
        public readonly ?string $user_actualiza = null,
        public readonly ?string $user_elimina = null,
        public readonly string $origen = 'API',
        ?string $nombre_completo_override = null
    ) {
        $this->nombre_completo = $nombre_completo_override
            ?? $this->nombres . ' ' . $this->apellido_paterno . ' ' . $this->apellido_materno;
    }

    public static function fromAPIResponse(array $response): self
    {
        // Validar y extraer la data de la respuesta

        if (empty($response) || !isset($response['numero'], $response['nombres'])) {
            throw new \Exception("No se recibieron datos de persona válidos para el mapeo.");
        }

        // Mapear y transformar los datos
        $fechaNacimientoBD = null;

        if (!empty($response['fecha_nacimiento'])) {
            try {
                $fechaNacimientoBD = Carbon::createFromFormat('d/m/Y', $response['fecha_nacimiento'])->format('Y-m-d');
            } catch (\Exception $e) {
                // Manejar error de formato de fecha si es necesario
            }
        }

        // Convertir el array de ubigeo a string
        $ubigeoString = is_array($response['ubigeo']) ? end($response['ubigeo']) : null;

        return new self(
            id_tipodocumento: 1,
            numero_documento: $response['numero'],
            nombres: $response['nombres'],
            apellido_paterno: $response['apellido_paterno'],
            apellido_materno: $response['apellido_materno'],
            departamento: $response['departamento'] ?? null,
            provincia: $response['provincia'] ?? null,
            distrito: $response['distrito'] ?? null,
            direccion: $response['direccion'] ?? null,
            direccion_completa: $response['direccion_completa'] ?? null,
            ubigeo_reniec: $response['ubigeo_reniec'] ?? null,
            ubigeo: $ubigeoString,
            fecha_nacimiento: $fechaNacimientoBD,
            estado_civil: $response['estado_civil'] ?? null,
            user_crea: $response['user_crea'] ?? null,
            user_actualiza: $response['user_actualiza'] ?? null,
            user_elimina: $response['user_elimina'] ?? null,
            sexo: $response['sexo'],
            origen: 'API',
        );
    }

    public function withNombreGrupo(string $nombreGrupo): self
    {
        // Clonar la instancia actual, sobrescribiendo solo la propiedad nombre_grupo
        return new self(
            id_tipodocumento: $this->id_tipodocumento,
            numero_documento: $this->numero_documento,
            nombres: $this->nombres,
            apellido_paterno: $this->apellido_paterno,
            apellido_materno: $this->apellido_materno,
            nombre_grupo: $nombreGrupo, // <-- El valor que se sobrescribe
            departamento: $this->departamento,
            provincia: $this->provincia,
            distrito: $this->distrito,
            direccion: $this->direccion,
            direccion_completa: $this->direccion_completa,
            ubigeo_reniec: $this->ubigeo_reniec,
            ubigeo: $this->ubigeo,
            fecha_nacimiento: $this->fecha_nacimiento,
            estado_civil: $this->estado_civil,
            user_crea: $this->user_crea,
            user_actualiza: $this->user_actualiza,
            user_elimina: $this->user_elimina,
            sexo: $this->sexo,
            origen: $this->origen,
            nombre_completo_override: $this->nombre_completo // Mantener el nombre completo
        );
    }

    public function withNombreCompleto(string $nuevoNombre): self
    {
        return new self(
            id_tipodocumento: $this->id_tipodocumento,
            numero_documento: $this->numero_documento,
            nombres: $this->nombres,
            apellido_paterno: $this->apellido_paterno,
            apellido_materno: $this->apellido_materno,
            departamento: $this->departamento,
            provincia: $this->provincia,
            distrito: $this->distrito,
            direccion: $this->direccion,
            direccion_completa: $this->direccion_completa,
            ubigeo_reniec: $this->ubigeo_reniec,
            ubigeo: $this->ubigeo,
            fecha_nacimiento: $this->fecha_nacimiento,
            estado_civil: $this->estado_civil,
            user_crea: $this->user_crea,
            user_actualiza: $this->user_actualiza,
            user_elimina: $this->user_elimina,
            sexo: $this->sexo,
            origen: $this->origen,
            nombre_completo_override: $nuevoNombre
        );
    }
}
