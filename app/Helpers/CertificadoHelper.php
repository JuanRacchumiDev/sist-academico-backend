<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class CertificadoHelper
{
    /**
     * Garantiza que la carpeta de caché de fuentes de DomPDF exista en storage/fonts.
     */
    public static function ensureFontCacheDirExists(): void
    {
        $fontDir = storage_path('fonts');
        if (!File::exists($fontDir)) {
            File::makeDirectory($fontDir, 0755, true);
        }
    }

    /**
     * Formatea una fecha a texto legible
     * Ejemplo: 18 de agosto de 2026
     */
    public static function fechaEnLetras(?string $fecha): string
    {
        if (!$fecha) {
            return '';
        }

        return Carbon::parse($fecha)->locale('es')->isoFormat('D [de] MMMM [del] YYYY');
    }

    /**
     * Convierte una fuente TTF/OTF local a base64 para inscrustarla en el CSS del PDF.
     */
    public static function getFontBase64(string $fontName): ?string
    {
        self::ensureFontCacheDirExists();

        $fullPath = public_path("fonts/{$fontName}");

        if (!File::exists($fullPath)) {
            return null;
        }

        $fontData = File::get($fullPath);
        // $extension = pathinfo($fullPath, PATHINFO_EXTENSION);
        // $mime = $extension === "otf" ? "font/otf" : "font/ttf";

        // return 'data:' . $mime . ';base64,' . base64_encode($fontData);
        return 'data:font/truetype;base64,' . base64_encode($fontData);
    }

    /**
     * Resuelve el nombre de la vista Blade según la clave o diseño de la plantilla
     */
    public static function resolveTemplateView(?string $disenio): string
    {
        $disenio = $disenio ?? 'default';
        $view = "pdf.certificados.{$disenio}";

        return view()->exists($view) ? $view : 'pdf.certificado';
    }

    public static function resolveTemplateDefault(string $tipoPrograma): string
    {
        $view = "pdf.{$tipoPrograma}.default";

        return $view;
    }

    /**
     * Calcula dinámicamente el font-size y line-height según la longitud estimada del texto.
     * 
     * @param string $texto Texto a evaluar
     * @param float $fontSizeBase Tamaño de fuente deseado si entra en 1 sola línea (px/pt)
     * @param float $longitudMaxima Longitud máxima del texto ingresado
     * @return array ['font_size' => int|float, 'line_height' => float]
     */
    public static function calcularEstilosTexto(
        string $texto,
        float $fontSizeBase,
        float $longitudMaxima = 46.0
        // float $anchoContenedorPrincipal = 850.0,
        // float $factorAnchoFuente = 0.58
    ): array {
        $textoLimpio = trim($texto);
        $longitudTexto = mb_strlen($textoLimpio);

        Log::info("Evaluando texto: '{$textoLimpio}'", [
            'longitud' => $longitudTexto,
            'fontSizeBase' => $fontSizeBase,
            'longitudMax'  => $longitudMaxima,
        ]);

        // Caso 1: Es menor o igual a la longitud máxima
        if ($longitudTexto <= $longitudMaxima) {
            $newFontSize = (int) round($fontSizeBase * 0.80);
            Log::info("Resultado: Caso 1 para '{$textoLimpio}'", ['fontSize' => $newFontSize]);
            return [
                'font_size' => $newFontSize,
                'line_height' => 1.0
            ];
        }

        // Caso 2: Mayor a la longitud máxima, pero menor o igual al doble
        if ($longitudTexto <= ($longitudMaxima * 2)) {
            $newFontSize = (int) round($fontSizeBase * 0.70);
            Log::info("Resultado: Caso 2 para '{$textoLimpio}'", ['fontSize' => $newFontSize]);
            return [
                'font_size'   => $newFontSize,
                'line_height' => 0.8
            ];
        }

        // Caso 3: Mayor al doble de la longitud máxima
        $newFontSize = (int) round($fontSizeBase * 0.50);
        Log::info("Resultado: Caso 3 para '{$textoLimpio}'", ['fontSize' => $newFontSize]);

        return [
            'font_size'   => $newFontSize,
            'line_height' => 0.8
        ];
    }
}
