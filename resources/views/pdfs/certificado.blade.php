<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificado de Estudios</title>
    <style>
        /* 1. CONFIGURACIÓN DE PÁGINA COMPLETA SIN MÁRGENES */
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

        /* 2. REGISTRO DE FUENTES PERSONALIZADAS DESDE BASE64 */
        @if(isset($fonts['alumno']))
        @font-face {
            font-family: 'FuenteAlumno';
            src: url("{{ $fonts['alumno'] }}");
            font-weight: normal;
            font-style: normal;
        }
        @endif

        @if(isset($fonts['programa']))
        @font-face {
            font-family: 'FuentePrograma';
            src: url("{{ $fonts['programa'] }}");
            font-weight: normal;
            font-style: normal;
        }
        @endif

        @if(isset($fonts['fechas']))
        @font-face {
            font-family: 'FuenteFechas';
            src: url("{{ $fonts['fechas'] }}");
            font-weight: normal;
            font-style: normal;
        }
        @endif

        /* 3. CAPA DE FONDO COMPLETA (FULL BLEED) */
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

        /* 4. CONTENEDOR PRINCIPAL */
        .cert-canvas {
            position: relative;
            width: 100%;
            height: 100%;
        }

        /* 5. CAPAS EN POSICIÓN ABSOLUTA (Ajusta las coordenadas top/left según tu diseño) */

        /* Nombre del Alumno */
        .layer-alumno {
            position: absolute;
            top: 280px; /* Ajustar verticalmente */
            left: 5%;
            width: 90%;
            text-align: center;
        }

        .txt-alumno {
            font-family: 'FuenteAlumno', 'Times New Roman', serif;
            font-size: 38px;
            color: #1a252f;
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }

        /* Programa Académico */
        .layer-programa {
            position: absolute;
            top: 380px; /* Ajustar verticalmente */
            left: 10%;
            width: 80%;
            text-align: center;
        }

        .txt-tipo-programa {
            font-size: 16px;
            color: #555555;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .txt-programa {
            font-family: 'FuentePrograma', 'Helvetica', sans-serif;
            font-size: 26px;
            color: #111111;
            font-weight: bold;
            line-height: 1.3;
            margin: 0;
        }

        /* Detalle de Fechas e Carga Lectiva */
        .layer-fechas {
            position: absolute;
            top: 480px; /* Ajustar verticalmente */
            left: 10%;
            width: 80%;
            text-align: center;
        }

        .txt-fechas {
            font-family: 'FuenteFechas', 'Arial', sans-serif;
            font-size: 15px;
            color: #333333;
            line-height: 1.6;
            margin: 0;
        }

        .txt-fecha-resaltada {
            font-family: 'FuenteFechas', 'Arial', sans-serif;
            font-weight: bold;
            color: #000000;
        }

        /* Fecha de Emisión */
        .layer-emision {
            position: absolute;
            top: 550px; /* Ajustar verticalmente */
            left: 10%;
            width: 80%;
            text-align: center;
        }

        .txt-emision {
            font-family: 'FuenteFechas', 'Arial', sans-serif;
            font-size: 13px;
            color: #666666;
            font-style: italic;
        }

        /* Código QR en posición absoluta */
        .layer-qr {
            position: absolute;
            bottom: 40px;
            right: 50px;
            width: 100px;
            height: 100px;
            text-align: center;
        }

        .layer-qr img {
            width: 100px;
            height: 100px;
        }

        .layer-qr .codigo-verif {
            font-size: 9px;
            color: #555;
            margin-top: 3px;
            font-family: monospace;
        }
    </style>
</head>
<body>

    <!-- FONDO DE LA PLANTILLA QUE OCUPA EL 100% DE ALTO Y ANCHO -->
    @if(isset($info->fondo) && $info->fondo)
        <div class="cert-background">
            <img src="{{ $info->fondo }}" alt="Fondo Certificado">
        </div>
    @endif

    <div class="cert-canvas">

        <!-- 1. NOMBRE DEL ALUMNO CON FUENTE PERSONALIZADA -->
        <div class="layer-alumno">
            <h1 class="txt-alumno">{{ $info->nombre_alumno }}</h1>
        </div>

        <!-- 2. NOMBRE DEL PROGRAMA ACADÉMICO CON FUENTE PERSONALIZADA -->
        <div class="layer-programa">
            <div class="txt-tipo-programa">{{ $info->nombre_tipoprograma }}</div>
            <h2 class="txt-programa">"{{ $info->titulo_programa }}"</h2>
        </div>

        <!-- 3. FECHAS DE INICIO Y FIN CON FUENTE PERSONALIZADA -->
        <div class="layer-fechas">
            <p class="txt-fechas">
                Desarrollado desde el 
                <span class="txt-fecha-resaltada">{{ $info->fecha_inicio_letras }}</span> 
                hasta el 
                <span class="txt-fecha-resaltada">{{ $info->fecha_final_letras }}</span>, 
                con una carga lectiva de <strong>{{ $info->numero_modulos }} módulos</strong> académicos.
            </p>
        </div>

        <!-- 4. FECHA DE EMISIÓN CON FUENTE PERSONALIZADA -->
        <div class="layer-emision">
            <span class="txt-emision">Emitido el {{ $info->fecha_emision }}</span>
        </div>

        <!-- 5. CÓDIGO QR Y VERIFICACIÓN -->
        @if(isset($info->qrCode) && $info->qrCode)
            <div class="layer-qr">
                <img src="{{ $info->qrCode }}" alt="Código QR">
                <div class="codigo-verif">{{ $info->codigo_verificacion }}</div>
            </div>
        @endif

    </div>

</body>
</html>