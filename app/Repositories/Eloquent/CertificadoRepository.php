<?php

namespace App\Repositories\Eloquent;

use App\DTOs\Certificado\CertificadoCreateDTO;
use App\DTOs\Certificado\CertificadoUpdateDTO;
use App\Models\Certificado;
use App\Repositories\Contracts\ICertificadoRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class CertificadoRepository implements ICertificadoRepository {
    public function getAllFiltered(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = Certificado::with([
            'persona',
            'tipoCertificado',
            'plantilla',
            'programa'
        ]);

        if (!empty($filters['id_persona'])) {
            $query->where('id_persona', $filters['id_persona']);
        }

        if (!empty($filters['id_tipocertificado'])) {
            $query->where('id_tipocertificado', $filters['id_tipocertificado']);
        }

        if (isset($filters['estado'])) {
            $query->where('estado', $filters['estado']);
        }

        if (!empty($filters['search'])) {
            $query->where('nombre_impresion', 'ILIKE', '%'.$filters['search'].'%');
        }

        return $query->paginate($perPage);
    }

    public function findById(int $id): ?Certificado
    {
        return Certificado::with([
            'persona',
            'tipoCertificado',
            'plantilla',
            'programa'
        ])->findOrFail($id);
    }

    public function create(array $data): Certificado
    {
        return Certificado::create($data);
    }

    public function update(int $id, array $data): ?Certificado
    {
        $certificado = $this->findById($id);

        if (!$certificado) {
            return null;
        }

        $certificado->update($data);

        return $certificado;
    }

    public function delete(int $id): bool
    {
        $certificado = $this->findById($id);

        if (!$certificado) return false;

        Storage::disk('local')->delete([$certificado->path_file.'/'.$certificado->filename, $certificado->codigo_qr_path]);
    
        return $certificado->delete();
    }
}