<?php

namespace App\Services;

use App\Services\Contracts\IStorageService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Exception;
use Override;

class StorageService implements IStorageService
{
    // protected string $disk;
    protected string $defaultDisk;

    public function __construct()
    {
        // Utiliza el disco configurado en el .env (FILESYSTEM_DISK=s3 o local)
        // $this->disk = config('filesystems.default', 'local');
        $this->defaultDisk = config('filesystems.default', 'local');
    }

    private function resolveDisk(?string $disk): string
    {
        return $disk ?? $this->defaultDisk;
    }

    public function put(string $path, string $content, string $visibility = 'private', ?string $disk = null): bool
    {
        $targetDisk = $this->resolveDisk($disk);
        return Storage::disk($targetDisk)->put($path, $content, $visibility);
    }

    public function get(string $path, ?string $disk = null): string
    {
        if (!$this->exists($path, $disk)) {
            throw new Exception("El archivo no existe en el almacenamiento: {$path}");
        }

        $targetDisk = $this->resolveDisk($disk);
        return Storage::disk($targetDisk)->get($path);
    }

    public function exists(string $path, ?string $disk = null): bool
    {
        $targetDisk = $this->resolveDisk($disk);
        return Storage::disk($targetDisk)->exists($path);
    }

    public function delete(string|array $paths, ?string $disk = null): bool
    {
        $targetDisk = $this->resolveDisk($disk);
        return Storage::disk($targetDisk)->delete($paths);
    }

    public function getUrl(string $path, ?int $expirationMinutes = null, ?string $disk = null): string
    {
        $targetDisk = $this->resolveDisk($disk);
        /** @var \Illuminate\Filesystem\FilesystemAdapter $diskAdapter */
        $diskAdapter = Storage::disk($targetDisk);

        if ($targetDisk === 's3' && $expirationMinutes) {
            return $diskAdapter->temporaryUrl($path, now()->addMinutes($expirationMinutes));
        }

        return $diskAdapter->url($path);
    }

    public function getLocalPath(string $path, ?string $disk = null): string
    {
        $targetDisk = $this->resolveDisk($disk);

        if (in_array($targetDisk, ['local', 'public'])) {
            /** @var \Illuminate\Filesystem\FilesystemAdapter $diskAdapter */
            return Storage::disk($targetDisk)->path($path);
        }

        // Si es S3, descarga el archivo temporalmente para su uso por DomPDF/FPDI
        $tempPath = sys_get_temp_dir() . '/' . basename($path);
        file_put_contents($tempPath, $this->get($path, $targetDisk));

        return $tempPath;
    }

    /**
     * Almacena un archivo en el disco especificado (o el por defecto)
     */
    public function putFile(string $path, UploadedFile $file, ?string $disk = null): bool
    {
        $selectedDisk = $disk ?? config('filesystems.default', 'local');

        return Storage::disk($selectedDisk)->putFileAs(
            dirname($path),
            $file,
            basename($path)
        ) !== false;
    }

    /**
     * Elimina un archivo del disco indicado
     */
    public function deleteFile(string $path, ?string $disk = null): bool
    {
        $selectedDisk = $disk ?? config('filesystems.default', 'local');

        if (Storage::disk($selectedDisk)->exists($path)) {
            return Storage::disk($selectedDisk)->delete($path);
        }

        return true;
    }
}
