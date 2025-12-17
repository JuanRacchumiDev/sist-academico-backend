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
        /* Estilo base para Dompdf */
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-size: 11pt;
        }
        .container {
            width: 100%;
            height: 100vh;
            padding: 50px;
            /* Marco decorativo */
            border: 5px solid #003366; 
            border-style: double;
        }
        .header {
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ccc;
        }
        .header h1 {
            color: #003366;
            font-size: 26pt;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .header h2 {
            color: #555;
            font-size: 14pt;
            margin-top: 5px;
            font-weight: normal;
        }
        .body-text {
            margin-top: 30px;
            text-align: justify;
            line-height: 1.8;
            font-size: 12pt;
        }
        .highlight {
            font-weight: bold;
            color: #000;
            text-transform: uppercase;
        }
        .program-title {
            text-align: center; 
            font-size: 16pt; 
            font-weight: bold; 
            color: #cc0000;
            border: 2px solid #cc0000;
            padding: 10px;
            margin: 25px 0;
            display: inline-block;
            width: 80%; /* Para que el borde no sea de 100% de ancho */
            margin-left: 10%;
            margin-right: 10%;
        }
        .data-table {
            width: 90%;
            margin: 30px auto 30px auto;
            border-collapse: collapse;
            font-size: 11pt;
        }
        .data-table th, .data-table td {
            padding: 10px 15px;
            border: 1px solid #e0e0e0;
            text-align: left;
        }
        .data-table th {
            background-color: #f0f4f7;
            color: #003366;
            width: 35%;
        }
        .footer-section {
            margin-top: 50px;
            padding-top: 20px;
            position: relative;
            height: 150px; /* Para contener los elementos flotantes */
        }
        .signature-block {
            width: 40%;
            float: right;
            text-align: center;
        }
        .signature-line {
            width: 100%;
            margin: 0 auto 5px auto;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        .qr-area {
            width: 40%;
            float: left;
            text-align: left;
            font-size: 9pt;
        }
        .qr-code-svg {
            margin-top: 5px;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $title }}</h1>
        </div>

        <div class="body-text">
            <p style="text-align: center;">El Director(a) de IPEDE, deja constancia que:</p>

            <p style="text-align: center; margin: 30px 0;">
                El(la) estudiante <span class="highlight">{{ $nombreCompleto }}</span>, identificado(a) con DNI N° <span class="highlight">{{ $numeroDocumento }}</span>, se encuentra registrado(a) y matriculado(a) en el programa académico de:
            </p>
            
            <p class="program-title">
                "{{ $nombrePrograma }}"
            </p>
            
            <p style="margin-top: 40px; text-align: justify;">
                La matrícula fue registrada el <span class="highlight">{{ $fechaMatricula }}</span>. A continuación, se detallan las características del programa:
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

        <div class="footer-section clearfix">
            <div class="signature-block">
                <div style="height: 50px;"></div> {{-- Espacio para el sello/firma --}}
                <div class="signature-line"></div>
                <p style="margin-top: 5px;">Firma y Sello de la Dirección Académica</p>
            </div>
            
            <div class="qr-area">
                {{-- <p style="margin: 0; padding: 0;">**Certificado Digital N°:** {{ $mIdPadded }}-{{ $pIdPadded }}</p> --}}
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