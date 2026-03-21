<?php
namespace App\Services\Contracts;

use App\DTOs\Certificado\CertificadoCreateDTO;
use App\DTOs\Certificado\CertificadoUpdateDTO;
use App\Models\Certificado;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ICertificadoService {
    public function getAllCertificadosWithFilters(array $filters, int $perPage): LengthAwarePaginator;
    public function getCertificadoById(int $id): ?Certificado;
    public function generatePDF(int $id);
    public function createCertificado(CertificadoCreateDTO $dto): Certificado;
    public function updateCertificado(int $id, CertificadoUpdateDTO $dto): ?Certificado;
    public function deleteCertificado(int $id): bool;
}