<?php
namespace App\DTOs\Pago;

use Spatie\LaravelData\Data;

class PagoCreateDTO extends Data
{
    public function __construct(
        public int $id_matricula,
        public int $id_alumno,
        public int $id_formapago,
        public int $id_metodopago,
        public int $id_estadopago,
        public string $concepto,
        public string $fecha_pago,
        public float $monto_efectivo = 0,
        public float $monto_tarjeta = 0,
        public float $monto_total,
        public float $monto_pagado,
        public float $monto_saldo = 0,
        public bool $estado = true,
        public ?int $id_programa = null,
        public ?string $nro_operacion = null,
        public ?int $numero_modulo = null,
        public ?string $user_crea = null,
        public ?string $user_actualiza = null,
        public ?string $user_elimina = null,
    ){}
}