<!-- /resources/views/pdf/capacitacion/default.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Certificado de Estudios</title>
    <style>
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
            page-break-after: always;
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
            top: 325px;
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
            top: 498px;
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

        /* --- HOJA 2 (Ajustes de tamaño de letra y QR) --- */
        .page-second {
            padding: 45px 55px;
            box-sizing: border-box;
            background-color: #ffffff;
            height: 100%;
        }

        .table-container {
            width: 100%;
            border-collapse: collapse;
        }

        .col-left {
            width: 62%;
            vertical-align: top;
            padding-right: 25px;
        }

        .col-right {
            width: 38%;
            vertical-align: top;
            text-align: right;
        }

        /* Fuente incrementada a 15px */
        .text-legal {
            font-size: 15px;
            color: #1a1a1a;
            line-height: 1.45;
            margin-bottom: 22px;
        }

        /* Título del Programa incrementado a 18px */
        .title-curso {
            font-size: 18px;
            font-weight: bold;
            color: #0b2239;
            margin-bottom: 16px;
        }

        /* Cabecera Temario incrementada a 15px */
        .temario-box {
            border: 1px dashed #000000;
            padding: 6px 12px;
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 12px;
            width: 95%;
        }

        /* Lista de Temario incrementada a 15px */
        .temario-list {
            margin: 0;
            padding-left: 0;
            list-style: none;
        }

        .temario-list li {
            font-size: 15px;
            color: #1a1a1a;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .logo-img {
            max-width: 210px;
            height: auto;
        }

        /* Cuadro de QR Agrandado */
        .qr-block {
            margin-top: 60px;
            width: 270px;
            float: right;
            border-collapse: collapse;
        }

        .qr-header-cell {
            border: 1px solid #000000;
            padding: 6px;
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            background-color: #ffffff;
        }

        .qr-body-cell {
            border: 1px solid #000000;
            padding: 6px 8px;
            font-size: 13px;
        }

        .qr-image-cell {
            border: 1px solid #000000;
            padding: 12px;
            text-align: center;
        }

        /* Tamaño del QR incrementado a 170px */
        .qr-image-cell img {
            width: 170px;
            height: 170px;
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
    </div>

    <!-- HOJA 2 -->
    <div class="page-second">
        <table class="table-container">
            <tr>
                <!-- Columna Izquierda: Texto Legal + Temario -->
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

                <!-- Columna Derecha: Logo + QR y Código -->
                <td class="col-right">
                    @if(isset($info->logo) && $info->logo)
                        <img src="{{ $info->logo }}" class="logo-img" alt="Logo Institución">
                    @endif

                    <table class="qr-block">
                        <tr>
                            <td colspan="2" class="qr-header-cell">
                                REGISTRO ELECTRÓNICO
                            </td>
                        </tr>
                        <tr>
                            <td class="qr-body-cell" style="width: 55%; font-weight: bold;">Código Validación</td>
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

</body>
</html>