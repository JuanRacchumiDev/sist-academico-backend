<?php

namespace App\Helpers;

use Carbon\Carbon;

class ItemPagoHelper
{
    public static function calcularFechaVencimiento(string $fechaBase, int $numeroModulo): string
    {
        return Carbon::parse($fechaBase)
            ->addMonths($numeroModulo)
            ->format("Y-m-d");
    }
}
