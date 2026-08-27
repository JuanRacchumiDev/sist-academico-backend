<?php

namespace App\Services\Contracts;

interface IStorageService
{
    /**
     * Guarda el contenido de un archivo en el disco configurado
     */
    public function put(string $path, string $content, string $visibility = 'private', ?string $disk = null): bool;

    /**
     * Obtiene el contenido binario de un archivo
     */
    public function get(string $path, ?string $disk = null): string;

    /**
     * Comprueba la existencia de un archivo
     */
    public function exists(string $path, ?string $disk = null): bool;

    /**
     * Elimina uno o varios archivos
     */
    public function delete(string|array $paths, ?string $disk = null): bool;

    /**
     * Retorna la URL pública o temporal (firmada) para visualizar/descargar
     */
    public function getUrl(string $path, ?int $expirationMinutes = null, ?string $disk = null): string;

    /**
     * Retorna la ruta absoluta local (si aplica) o descarga a un path temporal
     */
    public function getLocalPath(string $path, ?string $disk = null): string;
}
