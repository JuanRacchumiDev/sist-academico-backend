<?php
use Carbon\Carbon;

$numeroDocumento = $matricula['numero_documento'] ?? 'N/A';
$nombreCompleto = $matricula['nombre_completo'] ?? 'N/A';
$nombrePrograma = $matricula['nombre_programa'] ?? 'N/A';
$fechaMatricula = $matricula['fecha_matricula'] ? Carbon::parse($matricula['fecha_matricula'])->format('d/m/Y') : null;

// Variables del programa que deben ser evaluadas
$fechaInicio = $matricula['fecha_inicio'] ?? null;
$fechaFinal = $matricula['fecha_final'] ?? null;
$duracion = $matricula['duracion'] ?? null;
$horasAcademicas = $matricula['horas_academicas'] ?? null;
$modulos = $matricula['modulos'] ?? null;
$creditos = $matricula['creditos'] ?? null;
$modalidad = $matricula['modalidad'] ?? null;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-size: 11pt; /* Tamaño base de la letra */
        }
        .certificado {
            width: 100%;
            height: 100%; /* Ocupa toda la página A4 */
            padding: 50px 70px; /* Margen interior */
            text-align: center;
            /* Estilo de borde mejorado, si se desea */
            /* border: 1px solid #003366; */ 
        }
        .header {
            margin-bottom: 30px;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #003366;
            font-size: 24pt;
            margin: 0;
            text-transform: uppercase;
        }
        .header h2 {
            color: #cc0000;
            font-size: 16pt;
            margin-top: 5px;
            font-weight: normal;
        }
        .body-text {
            margin-top: 40px;
            text-align: justify;
            line-height: 1.8;
            font-size: 12pt;
        }
        .highlight {
            font-weight: bold;
            color: #000; /* Negro para formalidad */
            text-transform: uppercase;
        }
        .data-table {
            width: 90%;
            margin: 30px auto 30px auto;
            border-collapse: collapse;
            font-size: 11pt;
        }
        .data-table th, .data-table td {
            padding: 8px 15px;
            border: 1px solid #e0e0e0;
            text-align: left;
        }
        .data-table th {
            background-color: #f7f7f7;
            color: #003366;
            width: 35%;
        }
        .data-table td {
            font-weight: normal;
            color: #333;
        }
        .footer-section {
            margin-top: 40px;
            text-align: center;
            position: absolute; /* Para posicionar en el pie de página */
            bottom: 50px;
            left: 0;
            right: 0;
            padding: 0 70px;
        }
        .signature-line {
            width: 250px;
            margin: 0 auto 5px auto;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        .qr-area {
            position: absolute;
            bottom: 50px;
            left: 70px; /* Posicionar QR a la izquierda */
            text-align: left;
            font-size: 9pt;
        }
        .qr-code-svg {
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="certificado">
        <div class="header">
            <h1>{{ $title }}</h1>
        </div>

        <div class="body-text">
            <p>El Director(a) de IPEDE, deja constancia que:</p>

            <p style="text-align: center; margin: 30px 0;">
                El(la) estudiante <span class="highlight">{{ $nombreCompleto }}</span>, identificado(a) con DNI N° <span class="highlight">{{ $numeroDocumento }}</span>, se encuentra registrado(a) y matriculado(a) en el programa académico de:
            </p>
            
            <p style="text-align: center; font-size: 14pt; font-weight: bold; color: #003366; border: 1px solid #003366; padding: 10px;">
                "{{ $nombrePrograma }}"
            </p>
            
            <p style="margin-top: 40px;">
                La matrícula fue realizada con fecha <span class="highlight">{{ $fechaMatricula }}</span>. A continuación, se detallan las características del programa:
            </p>

            {{-- ------------------------------------------------ --}}
            {{-- Tabla con datos académicos y evaluación condicional --}}
            {{-- ------------------------------------------------ --}}
            <table class="data-table">
                <tbody>
                    @if ($fechaInicio)
                        <tr>
                            <th>Fecha de Inicio</th>
                            <td>{{ Carbon::parse($fechaInicio)->format('d/m/Y') }}</td>
                        </tr>
                    @endif
                    
                    @if ($fechaFinal)
                        <tr>
                            <th>Fecha de Finalización</th>
                            <td>{{ Carbon::parse($fechaFinal)->format('d/m/Y') }}</td>
                        </tr>
                    @endif
                    
                    @if ($duracion)
                        <tr>
                            <th>Duración</th>
                            <td>{{ $duracion }}</td>
                        </tr>
                    @endif

                    @if ($horasAcademicas)
                        <tr>
                            <th>Horas Académicas</th>
                            <td>{{ $horasAcademicas }} horas</td>
                        </tr>
                    @endif

                    @if ($modulos)
                        <tr>
                            <th>Módulos</th>
                            <td>{{ $modulos }}</td>
                        </tr>
                    @endif
                    
                    @if ($creditos)
                        <tr>
                            <th>Créditos</th>
                            <td>{{ $creditos }} créditos</td>
                        </tr>
                    @endif

                    @if ($modalidad)
                        <tr>
                            <th>Modalidad</th>
                            <td>{{ $modalidad }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <p style="margin-top: 40px; text-align: justify;">
                Se expide la presente a solicitud del interesado para los fines que estime conveniente.
            </p>
        </div>

        <div class="footer-section">
            <div style="width: 50%; float: right; text-align: center;">
                <div style="height: 50px;"></div> {{-- Espacio para el sello/firma --}}
                <div class="signature-line"></div>
                <p style="margin-top: 5px;">Firma y Sello de la Dirección Académica</p>
            </div>
            
            <div class="qr-area">
                {{-- <p style="margin: 0; padding: 0;">Certificado Digital N°: {{ $matricula->id_matricula }}-{{ $matricula->id_programa }}</p> --}}
                <p style="margin: 0; padding: 0;">Verificar validez escaneando el código:</p>
                <div class="qr-code-svg" style="width: 100px; height: 100px;">
                    {!! $qrCodeSvg !!}
                </div>
                <p style="font-size: 8pt; word-wrap: break-word; max-width: 120px; text-align: left; margin-top: 5px;">{{ $validationUrl }}</p>
            </div>
        </div>
    </div>
</body>
</html>