<?php
namespace App\Services;

use App\DTOs\Matricula\MatriculaCreateDTO;
use App\Models\Matricula;
use App\Repositories\Contracts\IDetalleParametroRepository;
use App\Repositories\Contracts\IMatriculaRepository;
use App\Repositories\Contracts\IPagoRepository;
use App\Repositories\Contracts\IPersonaRepository;
use App\Repositories\Contracts\IProgramaRepository;
use App\Repositories\Eloquent\DetalleParametroRepository;
use App\Repositories\Eloquent\PagoRepository;
use App\Repositories\Eloquent\PersonaRepository;
use App\Repositories\Eloquent\ProgramaRepository;
use App\Services\Contracts\IMatriculaService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;


class MatriculaService implements IMatriculaService {
    protected IMatriculaRepository $matriculaRepository;
    protected IPersonaRepository $personaRepository;
    protected IDetalleParametroRepository $detalleRepository;
    protected IProgramaRepository $programaRepository;
    protected IPagoRepository $pagoRepository;

    public function __construct(
        IMatriculaRepository $matriculaRepository,
        PersonaRepository $personaRepository,
        DetalleParametroRepository $detalleRepository,
        ProgramaRepository $programaRepository,
        PagoRepository $pagoRepository
        )
    {
        $this->matriculaRepository = $matriculaRepository;
        $this->personaRepository = $personaRepository;
        $this->detalleRepository = $detalleRepository;
        $this->programaRepository = $programaRepository;
        $this->pagoRepository = $pagoRepository;
    }

    public function getAllMatriculas(?array $searchParams = null): Collection
    {
        return $this->matriculaRepository->getAll($searchParams);
    }

    public function getAllMatriculasWithFilters(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->matriculaRepository->getAllFiltered($filters, $perPage);
    }

    public function getFichaByFilters(array $filters)
    {
        // Obtener datos de la matrícula
        $matricula = $this->matriculaRepository->getUniqueForFilters($filters);

        $empresa = [
            'razon_social' => 'COOPERATIVA DE SERVICIOS EDUCACIONALES CAPACITA',
            'ruc' => '20603337337'
        ];

        $pago = [
            'matricula' => '120.00',
            'cuotas' => '12',
            'monto_cuota' => '120.00',
            'total' => '1560.00'
        ];

        $dataPDF = [
            'title' => 'FICHA DE MATRÍCULA',
            'content' => [
                'matricula' => $matricula,
                'empresa' => $empresa,
                'pago' => $pago
            ]
        ];

        $pdf = Pdf::loadView('pdf.ficha', $dataPDF);

        $filename = 'matricula_0001.pdf';

        return $pdf->download($filename);
    }

    public function getMatriculaById(int $id): ?Matricula
    {
        return $this->matriculaRepository->findById($id);
    }

    public function createMatricula(MatriculaCreateDTO $matriculaCreateDTO): Matricula|null
    {
        // throw ValidationException::withMessages(['matriculaCreateDTO' => $matriculaCreateDTO]);

        return $this->matriculaRepository->create($matriculaCreateDTO);
    }

    /*
    public function createMatricula(MatriculaCreateDTO $matriculaCreateDTO): Matricula|null
    {
        $valorCuota = 0;
        $idFormaPago = 0;
        $idEstadoPago = 0;
        $montoTotal = 0;
        $montoPagado = 0;
        $montoSaldo = 0;

        $conceptoPago = 'PAGO DE MATRÍCULA';
        
        $data = array_filter($matriculaCreateDTO->toArray(), fn($value) => !is_null($value));
        
        $monto = $data['monto'];

        // Validando el id del alumno
        if (isset($data['id_alumno'])) {
            // Obteniendo el id del alumno
            $id_alumno = $data['id_alumno'];

            // Obteniendo datos del alumno
            $alumno = $this->personaRepository->findById($id_alumno);

            if ($alumno) {
                $data['nombre_alumno'] = $alumno->nombre_completo;
            }
        }

        // Validando el id de la sede
        if (isset($data['id_sede'])) {
            // Obteniendo el id de la sede
            $id_sede = $data['id_sede'];

            // Obteniendo datos de la sede
            $sede = $this->detalleRepository->findByCodigo($id_sede);

            if ($sede) {
                $data['nombre_sede'] = $sede->nombre;
            }
        }

        // Validando el id del programa
        if (isset($data['id_programa'])) {
            // Obteniendo el id del programa
            $id_programa = $data['id_programa'];

            // Obteniendo datos del programa
            $programa = $this->programaRepository->findById($id_programa);

            if ($programa) {
                $data['nombre_programa'] = $programa->nombre;
                $valorCuota = $programa->valor_cuota;
            }
        }

        // Validando el id del estado de matrícula
        if (isset($data['id_estadomatricula'])) {
            // Obteniendo el id del estado matrícula
            $id_estadomatricula = $data['id_estadomatricula'];

            // Obteniendo datos del estado de matrícula
            $estadoMatricula = $this->detalleRepository->findByCodigo($id_estadomatricula);

            if ($alumno) {
                $data['nombre_estadomatricula'] = $estadoMatricula->nombre;
            }
        }

        // throw ValidationException::withMessages(['data' => $data]);

        // return $this->matriculaRepository->create($data);

        // $idFormaPago = ($valorCuota == $monto) ? 33 : 34;

        // $idEstadoPago = ($valorCuota == $monto) ? 43 : 42;

        $formaPagoTotal = $this->detalleRepository->findByClaseAndNombreUrl(1007, 'pago-total');

        $formaPagoParcial = $this->detalleRepository->findByClaseAndNombreUrl(1007, 'pago-parcial');

        $estadoPagoTotal = $this->detalleRepository->findByClaseAndNombreUrl(1011, 'pago-total');

        $estadoPagoParcial = $this->detalleRepository->findByClaseAndNombreUrl(1011, 'pago-parcial');

        $idFormaPago = ($valorCuota == $monto) ? $formaPagoTotal->id : $formaPagoParcial->id;

        $idEstadoPago = ($valorCuota == $monto) ? $estadoPagoTotal->id : $estadoPagoParcial->id;

        $montoSaldo = ($valorCuota == $monto) ? 0 : ($valorCuota - $monto);

        $montoPagado = $monto;

        $montoTotal = $valorCuota;

        $newMatricula = $this->matriculaRepository->create($data);

        if ($newMatricula->id) {
            $idMatricula = $newMatricula->id;

            // Creando objeto para pago
            $payloadPago = [];
            $payloadPago['id_matricula'] = $idMatricula;
            $payloadPago['id_alumno'] = $newMatricula->id_alumno;
            $payloadPago['id_formago'] = $idFormaPago;
            $payloadPago['id_metodopago'] = $data['idMetodoPago'];
            $payloadPago['id_estadopago'] = $idEstadoPago;
            $payloadPago['concepto'] = $conceptoPago;
            $payloadPago['fecha_pago'] = $newMatricula['fecha_matricula'];
            $payloadPago['monto_efectivo'] = $montoPagado;
            $payloadPago['monto_tarjeta'] = 0;
            $payloadPago['monto_total'] = $montoTotal;
            $payloadPago['monto_pagado'] = $montoPagado;
            $payloadPago['monto_saldo'] = $montoSaldo;
            $payloadPago['estado'] = true;

            $newPago = $this->pagoRepository->create($payloadPago);

            if (isset($newPago->id)) {
                return $newMatricula;
            }

            return null;
        }

        return null;
    }
    */

    public function deleteMatricula(int $id): bool
    {
        $matricula = $this->matriculaRepository->findById($id);

        if (!$matricula) {
            return false;
        }

        return $this->matriculaRepository->delete($id);
    }
}