<?php

namespace App\Services\Contracts;

use App\DTOs\Adjunto\AdjuntoCreateDTO;
use App\Models\Adjunto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

interface IAdjuntoService
{
    public function getAllAdjuntos(?array $searchParams = null): Collection;
    public function getAllAdjuntosWithFilters(array $filters, int $perPage): LengthAwarePaginator;
    public function getAdjuntoById(int $id): ?Adjunto;
    public function getDownloadData(int $id): array;
    public function createAdjunto(array $data, UploadedFile $file): Adjunto;
    public function updateAdjunto(int $id, array $data, ?UploadedFile $file = null): ?Adjunto;
    public function obtenerAdjuntoByParams(int $idPrograma, ?int $idModulo, string $titulo): ?Adjunto;
}
