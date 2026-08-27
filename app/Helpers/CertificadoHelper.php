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
     * Calcula dinámicamente el font-size y line-height evaluando el ancho real proyectado en pt/px.
     * 
     * @param string $texto Texto a evaluar
     * @param float $fontSizeBase Tamaño de fuente base deseado
     * @param float $anchoMaximoDisponible Ancho disponible de la capa/caja en el PDF (por defecto 673.51 pt = 80% de A4 landscape)
     * @param float $factorFuente Factor de aspecto promedio de la fuente (GreatVibes/Calibri es aprox 0.38)
     * @return array ['font_size' => int|float, 'line_height' => float]
     */
    public static function calcularEstilosTexto(
        string $texto,
        float $fontSizeBase,
        float $anchoMaximoDisponible = 673.51,
        float $factorFuente = 0.38
    ): array {
        $textoLimpio = trim($texto);

        if ($textoLimpio === '') {
            return [
                'font_size' => (int) round($fontSizeBase * 0.80),
                'line_height' => 1.0
            ];
        }

        // Ponderación de caracteres según su ancho visual
        $anchoCaracteres = 0;
        $longitud = mb_strlen($textoLimpio);

        for ($i = 0; $i < $longitud; $i++) {
            $char = mb_substr($textoLimpio, $i, 1);

            if (preg_match('/[A-ZÑÁÉÍÓÚ]/u', $char)) {
                $anchoCaracteres += 1.25; // Mayúsculas
            } elseif (preg_match('/[mwW]/u', $char)) {
                $anchoCaracteres += 1.35; // Letras muy anchas
            } elseif (preg_match('/[iI1.,;\s]/u', $char)) {
                $anchoCaracteres += 0.45; // Caracteres muy angostos o espacios
            } elseif (preg_match('/[lftr]/u', $char)) {
                $anchoCaracteres += 0.65; // Letras angostas
            } else {
                $anchoCaracteres += 1.0;  // Minúsculas estándar (a, e, o, c, u, etc.)
            }
        }

        // Ancho total proyectado en la misma unidad que el PDF (pt/px)
        $anchoProyectado = $anchoCaracteres * $fontSizeBase * $factorFuente;

        Log::info("Evaluando tamaño para texto: '{$textoLimpio}'", [
            'longitudTexto'         => $longitud,
            'anchoCaracteres'       => $anchoCaracteres,
            'fontSizeBase'          => $fontSizeBase,
            'anchoProyectado'       => $anchoProyectado,
            'anchoMaximoDisponible' => $anchoMaximoDisponible
        ]);

        // CASO 1: El texto entra en 1 sola línea cómodamente sin reducir la fuente base
        if ($anchoProyectado <= $anchoMaximoDisponible) {
            $newFontSize = (int) round($fontSizeBase);
            Log::info("Resultado: Entra en 1 sola línea (Caso 1)", ['fontSize' => $newFontSize]);

            return [
                'font_size'   => $newFontSize,
                'line_height' => 1.0
            ];
        }

        // CASO 2: Requiere escalar la fuente o dividirse en 2 líneas (hasta 2x el ancho)
        if ($anchoProyectado <= ($anchoMaximoDisponible * 2)) {
            // Se calcula una reducción proporcional o se aplica un 75% del tamaño base
            $newFontSize = (int) round($fontSizeBase * 0.75);
            Log::info("Resultado: Requiere 2 líneas o escala moderada (Caso 2)", ['fontSize' => $newFontSize]);

            return [
                'font_size'   => $newFontSize,
                'line_height' => 0.95
            ];
        }

        // CASO 3: Texto muy largo (requiere 3 líneas o escala mayor)
        $newFontSize = (int) round($fontSizeBase * 0.55);
        Log::info("Resultado: Requiere 3 líneas o escala fuerte (Caso 3)", ['fontSize' => $newFontSize]);

        return [
            'font_size'   => $newFontSize,
            'line_height' => 0.85
        ];
    }
}
