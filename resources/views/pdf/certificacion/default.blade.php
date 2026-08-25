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

        /* Declaración única o múltiple especificando normal weight */
        @font-face {
            font-family: 'FuenteAlumno';
            src: url("{{ $fonts['alumno'] }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        
        @font-face {
            font-family: 'FuentePrograma';
            src: url("{{ $fonts['programa'] }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'FuenteFechas';
            src: url("{{ $fonts['fechas'] }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'FuenteDirector';
            src: url("{{ $fonts['director'] }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        /* CAPA DE FONDO COMPLETA (FULL BLEED) */
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

        /* CONTENEDOR PRINCIPAL */
        .cert-canvas {
            position: relative;
            width: 100%;
            height: 100%;
        }

        /* CAPAS Y FUENTES APLICADAS */
        .layer-alumno {
            /* border: 1px solid red; */
            position: absolute;
            top: 325px;
            left: 7%;
            width: 86%;
            text-align: center;
        }

        .txt-alumno {
            font-family: 'FuenteAlumno', sans-serif;
            /* font-size: 77px; */
            color: {{ $estilos['color_nombre_alumno'] ?? '#000000' }};
            /* line-height: 1.0; */
            margin: 0;
            padding: 0;
            font-weight: normal;
        }

        .layer-programa {
            /* border: 1px solid blue; */
            position: absolute;
            top: 460px;
            left: 10%;
            width: 80%;
            text-align: center;
        }

        .txt-programa {
            font-family: 'FuentePrograma', sans-serif;
            /* font-size: 31px; */
            color: {{ $estilos['color_nombre_programa'] ?? '#000000' }};
            /* font-weight: bold; */
            /* line-height: 1; */
            margin: 0;
            padding: 0;
            font-weight: normal;
        }

        .layer-fechas {
            /* border: 1px solid green; */
            position: absolute;
            top: 500px;
            left: 10%;
            width: 80%;
            text-align: center;
        }

        .txt-fechas {
            font-family: 'FuenteFechas', sans-serif;
            /* font-size: 17px; */
            color: {{ $estilos['color_fechas'] ?? '#000000' }};
            /* line-height: 1.6; */
            margin: 0;
            padding: 0;
            font-weight: normal;
        }

        .layer-director {
            /* border: 1px solid purple; */
            position: absolute;
            top: 690px;
            left: 30%;
            width: 21%;
            text-align: center;
        }

        .txt-director {
            font-family: 'FuenteDirector', sans-serif;
            font-size: 12px;
            color: #000;
            line-height: 1.6;
            margin: 0;
            font-weight: normal;
        }
    </style>
</head>
<body>
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
            <p class="txt-fechas" style="font-size: {{ $info->estilos_fechas['font_size'] }}px; line-height: {{ $info->estilos_fechas['line_height'] }};">{{ $info->fechas_programa }}, con una duración de {{ $info->horas_academicas }} horas</p>
        </div>

        <div class="layer-director">
            <p class="txt-director">{{ $info->nombre_director }}</p>
        </div>
    </div>
</body>
</html>