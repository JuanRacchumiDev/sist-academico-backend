<?php
namespace App\Services\Contracts;

use App\Models\Persona;

interface IPersonaAPIService {
    public function query(string $tipoDocumento, string $numeroDocumento): array;

    public function queryAndRegister(string $tipoDocumento, string $numeroDocumento): Persona;

    public function callAPI(string $numeroDocumento): array;
}