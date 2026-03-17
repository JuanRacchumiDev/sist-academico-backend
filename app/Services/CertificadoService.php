<?php

namespace App\Services;

use App\DTOs\Certificado\CertificadoCreateDTO;
use App\DTOs\Certificado\CertificadoUpdateDTO;
use App\Models\Certificado;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\ICertificadoRepository;
use App\Services\Contracts\ICertificadoService;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class CertificadoService implements ICertificadoService {
    protected ICertificadoRepository $certificadoRepository;
    
    public function __construct(ICertificadoRepository $certificadoRepository) {
        $this->certificadoRepository = $certificadoRepository;
    }

    public function getAllCertificadosWithFilters(array $filters, int $perPage): LengthAwarePaginator {
        return $this->certificadoRepository->getAllFiltered($filters, $perPage);
    }

    public function getCertificadoById(int $id): ?Certificado {
        return $this->certificadoRepository->findById($id);
    }

    public function createCertificado(CertificadoCreateDTO $dto): Certificado {
        $data = $dto->toArray();

        if (empty($data['codigo_verificacion'])) {
            $data['codigo_verificacion'] = strtoupper(uniqid('CERT-'));
        }

        return $this->certificadoRepository->create($data);
    }

    public function updateCertificado(int $id, CertificadoUpdateDTO $dto): ?Certificado {
        $certificado = $this->certificadoRepository->findById($id);

        if (!$certificado) {
            throw new \Exception("Certificado no encontrado", 404);
        }

        $data = array_filter($dto->toArray(), fn($value) => !is_null($value));

        return $this->certificadoRepository->update($id, $data);
    }
}