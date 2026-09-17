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

        @if(!empty($fonts['alumno']['custom_font']) && !empty($fonts['alumno']['path']))
        @font-face {
            font-family: 'FuenteAlumno';
            src: url("{{ $fonts['alumno']['path'] }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @endif
        
        @if(!empty($fonts['programa']['custom_font']) && !empty($fonts['programa']['path']))
        @font-face {
            font-family: 'FuentePrograma';
            src: url("{{ $fonts['programa']['path'] }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @endif

        @if(!empty($fonts['fechas']['custom_font']) && !empty($fonts['fechas']['path']))
        @font-face {
            font-family: 'FuenteFechas';
            src: url("{{ $fonts['fechas']['path'] }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @endif

        @if(!empty($fonts['director']['custom_font']) && !empty($fonts['director']['path']))
        @font-face {
            font-family: 'FuenteDirector';
            src: url("{{ $fonts['director']['path'] }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @endif

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

        /* CAPAS Y ESTILOS DINÁMICOS */
        .layer-alumno {
            border: 1px solid blue;
            position: absolute;
            top: 390px;
            left: 5%;
            width: 85%;
            text-align: center;
        }

        .txt-alumno {
            color: {{ $estilos['alumno']['color'] ?? '#000000' }};
            font-family: 'FuenteAlumno', sans-serif;
            font-size: {{ ($info->estilos_alumno['font_size'] ?? $estilos['alumno']['fontSize']) . 'px' }};
            line-height: {{ $info->estilos_alumno['line_height'] ?? 1.0 }};
            white-space: normal;
            word-wrap: break-word;
            overflow-wrap: break-word;
            text-align: center;
            margin: 0;
            padding: 0;
            font-weight: normal;
        }

        .layer-programa {
            border: 1px solid red;
            position: absolute;
            top: 240px;
            left: 53%;
            width: 38%;
            text-align: left;
        }

        .txt-programa {
            border: 1px solid #e21616;
            font-family: 'FuentePrograma', sans-serif;
            color: {{ $estilos['programa']['color'] ?? '#000000' }};
            font-size: {{ ($info->estilos_programa['font_size'] ?? $estilos['programa']['fontSize']) . 'px' }};
            line-height: {{ $info->estilos_programa['line_height'] ?? 1.0 }};
            white-space: normal;
            word-wrap: break-word;
            overflow-wrap: break-word;
            text-align: left;
            margin: 0;
            font-weight: normal;
        }

        .layer-fechas {
            border: 1px solid purple;
            position: absolute;
            top: 520px;
            left: 10%;
            width: 75%;
            text-align: center;
        }

        .txt-fechas {
            font-family: 'FuenteFechas', sans-serif;
            color: {{ $estilos['fechas']['color'] ?? '#000000' }};
            font-size: {{ ($info->estilos_fechas['font_size'] ?? $estilos['fechas']['fontSize']) . 'px' }};
            line-height: {{ $info->estilos_fechas['line_height'] ?? 1.0 }};
            white-space: normal;
            word-wrap: break-word;
            overflow-wrap: break-word;
            text-align: center;
            margin: 0;
            padding: 0;
            font-weight: normal;
        }

        @if(isset($estilos['director']))
        .layer-director {
            border: 1px solid green;
            position: absolute;
            top: 715px;
            left: 31%;
            width: 19%;
            text-align: center;
        }

        .txt-director {
            font-family: 'FuenteDirector', sans-serif;
            color: {{ $estilos['director']['color'] ?? '#000000' }};
            font-size: {{ ($info->estilos_director['font_size'] ?? $estilos['director']['fontSize']) . 'px' }};
            line-height: {{ $info->estilos_director['line_height'] ?? 1.0 }};
            white-space: normal;
            word-wrap: break-word;
            overflow-wrap: break-word;
            text-align: center;
            margin: 0;
            padding: 0;
            font-weight: normal;
        }
        @endif

        .page-second {
            page-break-before: always;
            padding-top: 45px;
            padding-bottom: 45px;
            background-color: #ffffff;
            width: 100%;
        }

        .wrapper-hoja2 {
            width: 90%;
            margin: 0 auto;
        }

        .table-container {
            width: 100%;
            border-collapse: collapse;
        }

        .col-left {
            width: 62%;
            vertical-align: top;
            padding-right: 30px;
            text-align: left;
        }

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

        .qr-block {
            width: 250px;
            border-collapse: collapse;
            margin-left: auto;
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
    <!-- Hoja 1 -->
    <div class="page-first">
        @if(isset($info->fondo) && $info->fondo)
            <div class="cert-background">
                <img src="{{ $info->fondo }}" alt="Fondo Certificado">
            </div>
        @endif

        <div class="cert-canvas">
            <div class="layer-alumno">
                <h1 class="txt-alumno">{{ $info->nombre_alumno }}</h1>
            </div>

            <div class="layer-programa">
                <h2 class="txt-programa">{{ $info->titulo_programa }}</h2>
            </div>

            <div class="layer-fechas">
                <p class="txt-fechas">{{ $info->fechas_programa }}</p>
            </div>

            @if(isset($estilos['director']))
            <div class="layer-director">
                <p class="txt-director">{{ $info->nombre_director }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- HOJA 2 -->
    <div class="page-second">
        <div class="wrapper-hoja2">
            <table class="table-container">
                <tr>
                    <!-- Columna Izquierda: Texto Legal + Titulo + Temario (Alineación Izquierda) -->
                    <td class="col-left">
                        <div class="text-legal">
                            Esta es una copia auténtica imprimible de un documento electrónico archivado por INNOVAPERU, aplicando lo dispuesto por el Art. 25 de D.S. 070-2013-PCM y la Tercera Disposición Complementaria Final del D.S. 026-2016-PCM.
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
                            @if(!empty($info->logo))
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