<!-- /resources/views/pdf/capacitacion/default.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Certificado de Estudios</title>
    <style>
        * {
            box-sizing: border-box;
        }

        @page {
            margin: 0px;
            size: A4 landscape;
        }

        html, body {
            margin: 0px;
            padding: 0px;
            width: 100%;
            height: 100%;
            background-color: #ffffff;
            font-family: 'Helvetica', 'Arial', sans-serif;
        }

        @font-face {
            font-family: 'FuenteAlumno';
            src: url("{{ $fonts['alumno'] }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        
        @if(!empty($fonts['is_custom_programa']))
        @font-face {
            font-family: 'FuentePrograma';
            src: url("{{ $fonts['programa'] }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @endif

        @if(!empty($fonts['is_custom_fechas']))
        @font-face {
            font-family: 'FuenteFechas';
            src: url("{{ $fonts['fechas'] }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @endif

        @font-face {
            font-family: 'FuenteDirector';
            src: url("{{ $fonts['director'] }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        /* --- HOJA 1 --- */
        .page-first {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .cert-background {
            position: absolute;
            top: 0px;
            left: 0px;
            width: 100%;
            height: 100%;
            z-index: -1000;
        }

        .cert-background img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .cert-canvas {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .layer-alumno {
            position: absolute;
            top: 315px;
            left: 7%;
            width: 86%;
            text-align: center;
        }

        .txt-alumno {
            font-family: 'FuenteAlumno', sans-serif;
            color: {{ $estilos['color_nombre_alumno'] ?? '#000000' }};
            margin: 0;
            padding: 0;
            font-weight: normal;
        }

        .layer-programa {
            position: absolute;
            top: 450px;
            left: 10%;
            width: 80%;
            text-align: center;
        }

        .txt-programa {
            font-family: {{ !empty($fonts['is_custom_programa']) ? "'FuentePrograma', sans-serif" : $fonts['programa'] }};
            color: {{ $estilos['color_nombre_programa'] ?? '#000000' }};
            margin: 0;
            padding: 0;
            font-weight: bold;
        }

        .layer-fechas {
            position: absolute;
            top: 493px;
            left: 10%;
            width: 80%;
            text-align: center;
        }

        .txt-fechas {
            font-family: {{ !empty($fonts['is_custom_fechas']) ? "'FuenteFechas', sans-serif" : $fonts['fechas'] }};
            color: {{ $estilos['color_fechas'] ?? '#000000' }};
            margin: 0;
            padding: 0;
            font-weight: normal;
        }

        /* --- HOJA 2 --- */
        .page-second {
            page-break-before: always;
            padding-top: 45px;
            padding-bottom: 45px;
            background-color: #ffffff;
            width: 100%;
        }

        /* Contenedor con ancho de 90% y centrado (margin left/right 5%) */
        .wrapper-hoja2 {
            width: 90%;
            margin: 0 auto;
        }

        .table-container {
            width: 100%;
            border-collapse: collapse;
        }

        /* Columna Izquierda: Ajustada al 62% del contenedor */
        .col-left {
            width: 62%;
            vertical-align: top;
            padding-right: 30px;
            text-align: left;
        }

        /* Columna Derecha: Ajustada al 38% del contenedor con alineación a la derecha */
        .col-right {
            width: 38%;
            vertical-align: top;
            text-align: right;
        }

        .text-legal {
            font-size: 13px;
            color: #1a1a1a;
            line-height: 1.4;
            margin-bottom: 20px;
        }

        .title-curso {
            font-size: 16px;
            font-weight: bold;
            color: #0b2239;
            margin-bottom: 14px;
        }

        .temario-box {
            border: 1px dashed #000000;
            padding: 5px 10px;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 10px;
            width: 100%;
        }

        .temario-list {
            margin: 0;
            padding-left: 0;
            list-style: none;
        }

        .temario-list li {
            font-size: 13px;
            color: #1a1a1a;
            margin-bottom: 5px;
            line-height: 1.35;
        }

        .logo-container {
            width: 100%;
            text-align: right;
            margin-bottom: 20px;
        }

        .logo-img {
            max-width: 220px;
            max-height: 85px;
            height: auto;
        }

        /* Cuadro del QR con ancho ideal (250px) para no desbordar */
        .qr-block {
            width: 250px;
            border-collapse: collapse;
            margin-left: auto; /* Empuja el bloque QR totalmente a la derecha */
            margin-top: 10px;
        }

        .qr-header-cell {
            border: 1px solid #000000;
            padding: 5px;
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            background-color: #ffffff;
        }

        .qr-body-cell {
            border: 1px solid #000000;
            padding: 5px 8px;
            font-size: 11px;
        }

        .qr-image-cell {
            border: 1px solid #000000;
            padding: 10px;
            text-align: center;
        }

        .qr-image-cell img {
            width: 145px;
            height: 145px;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>

    <!-- HOJA 1 -->
    <div class="page-first">
        @if(isset($info->fondo) && $info->fondo)
            <div class="cert-background">
                <img src="{{ $info->fondo }}" alt="Fondo Certificado">
            </div>
        @endif

        <div class="cert-canvas">
            <div class="layer-alumno">
                <h1 class="txt-alumno" style="font-size: {{ $info->estilos_alumno['font_size'] }}px; line-height: {{ $info->estilos_alumno['line_height'] }};">{{ $info->nombre_alumno }}</h1>
            </div>

            <div class="layer-programa">
                <h2 class="txt-programa" style="font-size: {{ $info->estilos_programa['font_size'] }}px; line-height: {{ $info->estilos_programa['line_height'] }};">{{ $info->titulo_programa }}</h2>
            </div>

            <div class="layer-fechas">
                <p class="txt-fechas" style="font-size: {{ $info->estilos_fechas['font_size'] }}px; line-height: {{ $info->estilos_fechas['line_height'] }};">{{ $info->fechas_programa }}</p>
            </div>
        </div>
    </div><!-- HOJA 2 --><div class="page-second">
        <div class="wrapper-hoja2">
            <table class="table-container">
                <tr>
                    <!-- Columna Izquierda: Texto Legal + Titulo + Temario (Alineación Izquierda) -->
                    <td class="col-left">
                        <div class="text-legal">
                            Esta es una copia auténtica imprimible de un documento electrónico archivado por PerúAgro, aplicando lo dispuesto por el Art. 25 de D.S. 070-2013-PCM y la Tercera Disposición Complementaria Final del D.S. 026-2016-PCM.
                        </div>

                        <div class="title-curso">
                            {{ $info->titulo_programa }}
                        </div>

                        @if(!empty($info->temario))
                            <div class="temario-box">
                                Temario
                            </div>

                            <ul class="temario-list">
                                @if(is_array($info->temario))
                                    @foreach($info->temario as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                @else
                                    <li>{!! nl2br(e($info->temario)) !!}</li>
                                @endif
                            </ul>
                        @endif
                    </td>

                    <!-- Columna Derecha: Logo + Bloque QR (Alineación Derecha) -->
                    <td class="col-right">
                        <div class="logo-container">
                            @php
                                $logoNombre = $info->logo ?? 'logo.png'; 
                                $logoPath = public_path('images/' . ltrim($logoNombre, '/'));
                                
                                if (!file_exists($logoPath) && isset($info->logo) && file_exists(public_path($info->logo))) {
                                    $logoPath = public_path($info->logo);
                                }
                            @endphp

                            @if(file_exists($logoPath))
                                <img src="data:image/png;base64,{{ base64_encode(file_get_contents($logoPath)) }}" class="logo-img" alt="Logo Institución">
                            @elseif(isset($info->logo) && filter_var($info->logo, FILTER_VALIDATE_URL))
                                <img src="{{ $info->logo }}" class="logo-img" alt="Logo Institución">
                            @endif
                        </div>

                        <table class="qr-block">
                            <tr>
                                <td colspan="2" class="qr-header-cell">
                                    REGISTRO ELECTRÓNICO
                                </td>
                            </tr>
                            <tr>
                                <td class="qr-body-cell" style="width: 55%; font-weight: bold; text-align: left;">Código Validación</td>
                                <td class="qr-body-cell" style="width: 45%; text-align: center;">{{ $info->codigo_verificacion }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="qr-header-cell">
                                    VERIFICACIÓN EN LÍNEA
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="qr-image-cell">
                                    @if(isset($info->qrCode) && $info->qrCode)
                                        <img src="{{ $info->qrCode }}" alt="Código QR">
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>

</body>
</html>