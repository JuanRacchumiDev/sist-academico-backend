<?php
namespace App\Services;

use App\DTOs\Adjunto\AdjuntoCreateDTO;
use App\Models\Adjunto;
use App\Repositories\Contracts\IAdjuntoRepository;
use App\Services\Contracts\IAdjuntoService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class AdjuntoService implements IAdjuntoService {
    protected IAdjuntoRepository $adjuntoRepository;

    public function __construct(IAdjuntoRepository $adjuntoRepository) {
        $this->adjuntoRepository = $adjuntoRepository;
    }

    public function getAllAdjuntos(?array $searchParams = null): Collection {
        return $this->adjuntoRepository->getAll($searchParams);
    }

    public function getAllAdjuntosWithFilters(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->adjuntoRepository->getAllFiltered($filters, $perPage);
    }

    public function getAdjuntoById(int $id): ?Adjunto
    {
        return $this->adjuntoRepository->findById($id);
    }

    public function createAdjunto(array $data, UploadedFile $file): Adjunto {
        return DB::transaction(function() use ($data, $file){

            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('adjuntos', $filename, 'public');

            $fullData = array_merge($data, [
                'titulo_url' => Str::slug($data['titulo']),
                'filename' => $filename,
                'originalname' => $file->getClientOriginalName(),
                'filepath' => $path,
                'mimetype' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'es_descargable' => filter_var($data['es_descargable'] ?? true, FILTER_VALIDATE_BOOLEAN),
                'es_visible' => filter_var($data['es_visible'] ?? true, FILTER_VALIDATE_BOOLEAN),
                'estado' => true
            ]);

            $adjuntoCreateDTO = AdjuntoCreateDTO::from($fullData);

            $dataToCreate = $adjuntoCreateDTO->toArray();

            $adjunto = $this->adjuntoRepository->create($dataToCreate);

            return $adjunto;
        });
    }
}