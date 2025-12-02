<?php
namespace App\DTOs\Persona;

use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class PersonaAPIDTO {
    public readonly string $nombre_completo;

    public function __construct(
        public readonly int $id_tipodocumento,
        public readonly string $numero_documento,
        public readonly string $nombres,
        public readonly string $apellido_paterno,
        public readonly string $apellido_materno,
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
        public readonly ?string $sexo = null,
        public readonly string $origen = 'API',
        ?string $nombre_completo_override = null
    ){
        $this->nombre_completo = $nombre_completo_override 
            ?? $this->nombres.' '.$this->apellido_paterno.' '.$this->apellido_materno;
    }

    public static function fromAPIResponse(array $response, string $tipoDocumento): self
    {
        // Validar y extraer la data de la respuesta
        if (!isset($response['data']) || $response['status'] !== 200 || $response['success'] !== true) {
            throw new \Exception("La consulta de la API falló o no devolvió datos válidos.");
        }

        $data = $response['data'];

        // Mapear y transformar los datos
        $fechaNacimientoBD = null;

        if (!empty($data['fecha_nacimiento'])) {
            try {
                $fechaNacimientoBD = Carbon::createFromFormat('d/m/Y', $data['fecha_nacimiento'])->format('Y-m-d');
            } catch (\Exception $e) {
                // Manejar error de formato de fecha si es necesario
            }
        }

        // Convertir el array de ubigeo a string
        $ubigeoString = is_array($data['ubigeo']) ? end($data['ubigeo']) : null;

        return new self(
            id_tipodocumento: 1,
            numero_documento: $data['numero'],
            nombres: $data['nombres'],
            apellido_paterno: $data['apellido_paterno'],
            apellido_materno: $data['apellido_materno'],
            departamento: $data['departamento'] ?? null,
            provincia: $data['provincia'] ?? null,
            distrito: $data['distrito'] ?? null,
            direccion: $data['direccion'] ?? null,
            direccion_completa: $data['direccion_completa'] ?? null,
            ubigeo_reniec: $data['ubigeo_reniec'] ?? null,
            ubigeo: $ubigeoString,
            fecha_nacimiento: $fechaNacimientoBD,
            estado_civil: $data['estado_civil'] ?? null,
            sexo: $data['sexo'] ?? null,
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
            sexo: $this->sexo,
            origen: $this->origen,
            // Sobreescribir el valor de nombre_completo usando el parámetro de override
            nombre_completo_override: $nuevoNombre 
        );
    }
}