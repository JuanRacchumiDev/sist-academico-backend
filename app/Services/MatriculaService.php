<?php

namespace App\Services;

use App\DTOs\Matricula\MatriculaCreateDTO;
use App\DTOs\Matricula\MatriculaUpdateDTO;
use App\DTOs\Pago\PagoCreateDTO;
use App\DTOs\User\UserCreateDTO;
use App\Helpers\ItemPagoHelper;
use App\Models\Matricula;
use App\Repositories\Contracts\IMatriculaRepository;
use App\Repositories\Contracts\IPagoRepository;
use App\Repositories\Contracts\IUserRepository;
use App\Repositories\Contracts\IPersonaRepository;
use App\Repositories\Contracts\IDetalleParametroRepository;
use App\Services\Contracts\IMatriculaService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Mail\MatriculaConfirmadaMail;
use Illuminate\Support\Facades\Mail;
use Override;

class MatriculaService implements IMatriculaService
{
    protected IMatriculaRepository $matriculaRepository;
    protected IPagoRepository $pagoRepository;
    protected IUserRepository $userRepository;
    protected IPersonaRepository $personaRepository;
    protected IDetalleParametroRepository $detalleRepository;

    public function __construct(
        IMatriculaRepository $matriculaRepository,
        IPagoRepository $pagoRepository,
        IUserRepository $userRepository,
        IPersonaRepository $personaRepository,
        IDetalleParametroRepository $detalleRepository
    ) {
        $this->matriculaRepository = $matriculaRepository;
        $this->pagoRepository = $pagoRepository;
        $this->userRepository = $userRepository;
        $this->personaRepository = $personaRepository;
        $this->detalleRepository = $detalleRepository;
    }

    /**
     * Obtener todas las matrículas
     * @param array<string, mixed>|null $searchParams
     * @return Collection<int, Matricula>
     */
    public function getAllMatriculas(?array $searchParams = null): Collection
    {
        Log::info('Obteniendo matrícula registrada', ['searchParams' => $searchParams]);

        return $this->matriculaRepository->getAll($searchParams);
    }

    /**
     * Obtiene todos las matrículas con filtros aplicados
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllMatriculasWithFilters(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->matriculaRepository->getAllFiltered($filters, $perPage);
    }

    /**
     * Obtiene una matrícula por ID
     * @param int $id
     * @return Matricula|null
     */
    public function getMatriculaById(int $id): ?Matricula
    {
        return $this->matriculaRepository->findById($id);
    }

    /**
     * Verifica si una persona ya cuenta con una matrícula en una fecha determinada
     * @param int $idPersona
     * @param string $fechaMatricula
     * @return Matricula|null
     */
    public function getMatriculaByPersonaAndFecha(int $idPersona, string $fechaMatricula): ?Matricula
    {
        Log::info('Validando existencia de matrícula', [
            'id_persona' => $idPersona,
            'fecha_matricula' => $fechaMatricula
        ]);

        return $this->matriculaRepository->findByPersonaAndFecha($idPersona, $fechaMatricula);
    }

    public function generateFichaPDF(int $id)
    {
        $matricula = $this->matriculaRepository->findById($id);
        $institucion = $matricula->institucion;

        $numeroFormateado = str_pad($matricula->id, 4, '0', STR_PAD_LEFT);

        // Definir la ruta: año/institucion/documento/ficha.php
        $anio = Carbon::parse($matricula->fecha_matricula)->year;
        // $nombreIns = strtolower($dataInstitucion->nombre) ?? 'genérico';
        $nombreIns = str($institucion->nombre)->slug();
        $documento = $matricula->persona->numero_documento;

        $folderPath = "matriculas/{$anio}/{$nombreIns}/{$documento}";
        $fileName = "ficha_matricula_{$numeroFormateado}.pdf";
        $fullPath = "{$folderPath}/{$fileName}";

        // Verificar si ya existe
        if (Storage::disk("local")->exists($fullPath)) {
            return Storage::disk("local")->path($fullPath);
        }

        // Si no existe, generarlo

        // Generar QR en Base64 para embeberlo en el HTML
        $qrData = route('programas.show', $matricula->id);
        $qrCode = base64_encode(QrCode::format('svg')->size(100)->margin(0)->generate($qrData));

        $logoPath = $institucion->logo_path
            ? storage_path("app/public/" . $institucion->logo_path)
            : public_path("images/logo_default.png");

        $logoBase64 = null;

        if (file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);
            $logoBase64 = "data:image/" . $type . ';base64,' . base64_encode($data);
        }

        $data = [
            'matricula' => $matricula,
            'numero_registro' => $numeroFormateado,
            'institucion' => $institucion,
            'qrCode' => $qrCode,
            'fecha' => now()->format('d/m/Y H:i A'),
            'logo' => $logoBase64,
            'periodo' => $this->getPeriodoAcademico()
        ];

        $pdf = Pdf::loadView('pdf.ficha_matricula', $data)
            ->setPaper('a4', 'portrait');
        // ->setWarnings(false);

        Storage::disk('local')->put($fullPath, $pdf->output());
        return Storage::disk('local')->path($fullPath);
    }

    public function generateCertificadoPDF(int $idMatricula, int $idPrograma)
    {
        $data = $this->matriculaRepository->getCertificado($idMatricula, $idPrograma);

        if (!$data) {
            throw new Exception("No se encontraron registros válidos para generar el certificado.");
        }

        // Formatear fechas de manera profesional
        Carbon::setLocale("es");

        $data->fecha_inicio_letras = Carbon::parse($data->fecha_inicio)->translatedFormat('d \d\e F \d\e Y');
        $data->fecha_final_letras = Carbon::parse($data->fecha_final)->translatedFormat('d \d\e F \d\e Y');
        $data->fecha_emision = Carbon::now()->translatedFormat('d \d\e F \d\e Y');

        // Cargar vista blade y pasar los datos orientados de forma horizontal (landscape)
        $pdf = Pdf::loadView('pdf.certificado', ['info' => $data])
            ->setPaper('a4', 'landscape');

        return $pdf->output();
    }

    public function getModulosPorPagar(int $idMatricula): array
    {
        $matricula = $this->matriculaRepository->findById($idMatricula);

        if (!$matricula) {
            return [
                'status' => false,
                'message' => 'La matrícula especificada no existe',
                'detalle' => null,
                'code' => 404
            ];
        }

        $modulos = $this->pagoRepository->getModulosPorPagar($idMatricula, $matricula->numero_modulos);

        return [
            'status' => true,
            'message' => 'Módulos por pagar encontrados',
            'detalle' => [
                'matricula_id' => $idMatricula,
                'total_modulos' => $matricula->numero_modulos,
                'modulos' => $modulos
            ],
            'code' => 200
        ];
    }

    public function getModulosPagados(int $idMatricula): array
    {
        $matricula = $this->matriculaRepository->findById($idMatricula);

        if (!$matricula) {
            return [
                'status' => false,
                'message' => 'La matrícula especificada no existe',
                'detalle' => null,
                'code' => 404
            ];
        }

        $modulosPagados = $this->pagoRepository->getModulosPagados($idMatricula);

        return [
            'status' => true,
            'message' => 'Módulos pagados encontrados',
            'detalle' => [
                'matricula_id' => $idMatricula,
                'modulos' => $modulosPagados
            ],
            'code' => 200
        ];
    }

    public function generarCronogramaPagos(int $idMatricula)
    {
        $matricula = $this->matriculaRepository->findById($idMatricula);

        Log::info('Validando variable $matricula', ['matricula' => $matricula]);

        if (!$matricula) {
            return [
                'status' => false,
                'message' => 'La matrícula especificada no existe',
                'detalle' => null,
                'code' => 404
            ];
        }

        // Definición de datos por defecto de la Institución si vienen vacíos o nulos
        $institucion = $matricula->institucion;
        $institucionData = [
            'nombre' => $institucion->nombre ?? 'INSTITUCIÓN ACADÉMICA',
            'sigla' => $institucion->sigla ?? 'Innovación y aprendizaje continuo para ti',
            'telefono' => $institucion->telefono_contacto ?? '999-999-999',
            'email' => $institucion->email ?? 'contacto@institucion.edu.pe',
            'logo' => ($institucion && $institucion->logo_path)
                ? public_path('storage/' . $institucion->logo_path)
                : public_path('images/default_logo.png') // Asegúrate de tener una imagen por defecto o usa un placeholder base64
        ];

        // Si el logo físico no existe en la ruta de almacenamiento, usamos una alternativa base64 o texto
        if (!file_exists($institucionData['logo'])) {
            $institucionData['logo'] = null; // En la vista Blade manejaremos el fallback si es null
        }

        $pagosReales = $this->pagoRepository->getPagosByMatricula($idMatricula);

        Log::info('Obteniendo pagos reales', ['pagosReales' => $pagosReales]);

        $cronograma = [];

        $totalModulos = $matricula->numero_modulos;

        for ($i = 1; $i <= $totalModulos; $i++) {
            $pagoEfectuado = $pagosReales->firstWhere('numero_modulo', $i);

            $fechaVencimiento = ItemPagoHelper::calcularFechaVencimiento($matricula->fecha_matricula, $i);

            if ($pagoEfectuado) {
                $cronograma[] = [
                    'numero_modulo' => $i,
                    'fecha_vencimiento' => $fechaVencimiento,
                    'estado' => 'PAGADO',
                    'monto' => ($pagoEfectuado->cantidad_efectivo ?? 0) + ($pagoEfectuado->cantidad_operacion ?? 0),
                    'fecha_pago' => $pagoEfectuado->fecha_pago,
                    'referencia' => $pagoEfectuado->numero_operacion ?? 'Efectivo',
                    'forma_pago' => $pagoEfectuado->formaPago->nombre ?? 'N/A'
                ];
            } else {
                $cronograma[] = [
                    'numero_modulo' => $i,
                    'fecha_vencimiento' => $fechaVencimiento,
                    'estado' => 'PENDIENTE',
                    'monto' => 0.00,
                    'fecha_pago' => '---',
                    'referencia' => '---',
                    'forma_pago' => '---'
                ];
            }
        }

        $dataPdf = [
            'matricula' => $matricula,
            'institucion' => $institucionData,
            'cronograma' => $cronograma,
            'fecha_emision' => now()->format('d/m/Y H:i')
        ];

        Log::info('Evaluando variable dataPdf', ['dataPdf' => $dataPdf]);

        $pdf = Pdf::loadView('pdfs.cronograma_pagos', $dataPdf)
            ->setPaper('a4', 'portrait');

        return $pdf->output();
    }

    public function deleteFichaPDF(int $id): bool
    {
        $matricula = $this->matriculaRepository->findById($id);
        if (!$matricula) return false;

        $anio = \Carbon\Carbon::parse($matricula->fecha_matricula)->year;
        $institucion = "innovaperu";
        $documento = $matricula->persona->numero_documento;

        $fullPath = "matriculas/{$anio}/{$institucion}/{$documento}/ficha_matricula_{$id}.pdf";

        if (Storage::disk('local')->exists($fullPath)) {
            return Storage::disk('local')->delete($fullPath);
        }

        return true;
    }

    /**
     * Crear una nueva matrícula
     * @param MatriculaCreateDTO $matriculaCreateDTO
     * @return Matricula
     */
    public function createMatricula(MatriculaCreateDTO $matriculaCreateDTO): Matricula
    {
        return DB::transaction(function () use ($matriculaCreateDTO) {
            Log::info('Evaluando las variables $matriculaCreateDTO', ['matriculaCreateDTO' => $matriculaCreateDTO]);

            $idPersona = $matriculaCreateDTO->id_persona;

            $persona = $this->personaRepository->findById($idPersona);

            $userCrea = $matriculaCreateDTO->user_crea;
            $estado = $matriculaCreateDTO->estado;
            $valorMatricula = $matriculaCreateDTO->monto_matricula;
            $valorModulo = $matriculaCreateDTO->monto_modulo;
            $fechaMatricula = $matriculaCreateDTO->fecha_matricula;
            $idInstitucion = $matriculaCreateDTO->id_institucion;

            $dataCabecera = [
                'id_persona' => $idPersona,
                'id_estadomatricula' => $matriculaCreateDTO->id_estadomatricula,
                'id_institucion' => $idInstitucion,
                'numero_modulos' => $matriculaCreateDTO->numero_modulos,
                'fecha_matricula' => $fechaMatricula,
                'estado' => $estado,
                'user_crea' => $userCrea
            ];

            Log::info('Evaluando las variables persona y dataCabecera', ['persona' => $persona, 'dataCabecera' => $dataCabecera]);

            // Crear el registro cabecera de matrícula
            /** @var Matricula $matricula */
            $matricula = $this->matriculaRepository->create($dataCabecera);

            Log::info('Evaluando variable matrícula', ['matricula' => $matricula]);

            // Registrar los programas asignados
            foreach ($matriculaCreateDTO->programas as $idPrograma) {
                $matricula->detalles()->create([
                    'id_programa' => $idPrograma,
                    'valor_matricula' => $valorMatricula,
                    'valor_modulo' => $valorModulo,
                    'user_crea' => $userCrea,
                    'estado' => true
                ]);
            }

            $pagoMatriculaDTO = PagoCreateDTO::from([
                'id_matricula'      => $matricula->id,
                'id_formapago'      => $matriculaCreateDTO->id_formapago_matricula,
                'id_institucion'    => $idInstitucion,
                'concepto'          => $matriculaCreateDTO->concepto_matricula ?? 'PAGO DE MATRÍCULA',
                'numero_operacion'  => $matriculaCreateDTO->numero_operacion_matricula,
                'fecha_pago'        => $fechaMatricula,
                'cantidad_efectivo' => $matriculaCreateDTO->monto_efectivo_matricula,
                'cantidad_operacion' => $matriculaCreateDTO->monto_operacion_matricula,
                'user_crea'         => $userCrea,
                'estado'            => $estado
            ]);

            Log::info('Creando pago de Matrícula', ['pagoMatriculaDTO' => $pagoMatriculaDTO]);

            $this->pagoRepository->create($pagoMatriculaDTO->toArray());

            // Crear opcionalmente el pago del módulo 1
            if ($matriculaCreateDTO->pagarPrimerModulo) {
                $pagoModuloDTO = PagoCreateDTO::from([
                    'id_matricula'       => $matricula->id,
                    'id_formapago'       => $matriculaCreateDTO->id_formapago_modulo,
                    'id_institucion'     => $idInstitucion,
                    'concepto'           => $matriculaCreateDTO->concepto_modulo ?? 'PAGO DE MÓDULO #1',
                    'numero_modulo'      => 1,
                    'numero_operacion'   => $matriculaCreateDTO->numero_operacion_modulo,
                    'fecha_pago'         => $fechaMatricula,
                    'cantidad_efectivo'  => $matriculaCreateDTO->monto_efectivo_modulo,
                    'cantidad_operacion' => $matriculaCreateDTO->monto_operacion_modulo,
                    'user_crea'          => $userCrea,
                    'estado'             => $estado
                ]);

                Log::info('Creando pago de Módulo 1', ['pagoModuloDTO' => $pagoModuloDTO]);
                $this->pagoRepository->create($pagoModuloDTO->toArray());
            }

            // Obteniendo la clase del grupo perfil
            $clase = config('params.clases.perfil');

            // Obteniendo perfil de la persona
            $perfil = $this->detalleRepository->findByClaseAndNombreUrl($clase, 'alumno');

            $userCreateData = [
                'name'          => substr($persona->nombre_completo, 0, 10),
                'email'         => $persona->email,
                'password'      => $persona->numero_documento,
                'id_perfil'     => $perfil->codigo,
                'id_persona'    => $persona->id,
                'estado'        => true
            ];

            Log::info('Evaluando variable userCreateData', ['userCreateData' => $userCreateData]);

            // Creamos el usuario
            $this->userRepository->create($userCreateData);

            // Obtener la matrícula registrada
            $dataMatricula = $this->matriculaRepository->findById($matricula->id);

            Mail::to($persona->email)->send(
                new MatriculaConfirmadaMail($dataMatricula, $persona->numero_documento)
            );

            return $matricula->load(['detalles.programa']);
        });
    }

    public function updateMatricula(int $id, MatriculaUpdateDTO $dto): ?Matricula
    {
        return DB::transaction(function () use ($id, $dto) {
            $data = array_filter($dto->toArray(), fn($v) => !is_null($v));

            $matricula = $this->matriculaRepository->update($id, $data);

            if (isset($data['programas'])) {
                $matricula->detalles()->delete();

                foreach ($data['programas'] as $programaId) {
                    $matricula->detalles()->create([
                        'id_programa' => $programaId,
                        'user_crea' => $data['user_actualiza'] ?? null,
                        'estado' => true
                    ]);
                }
            }

            return $matricula->load('detalles');
        });
    }

    private function getPeriodoAcademico(): string
    {
        $mes = now()->month;
        return now()->year . ($mes <= 7 ? ' - I' : ' - II');
    }
}
